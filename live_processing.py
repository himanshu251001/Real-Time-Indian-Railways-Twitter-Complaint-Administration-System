from pyspark import SparkConf, SparkContext
from pyspark.mllib.classification import NaiveBayesModel
from pyspark.mllib.feature import HashingTF, IDF
from pyspark.streaming import StreamingContext
import pickle
import MySQLdb


def write_to_file(data):
    with open("C:/Users/Pratush/Downloads/SDP 2024/data/output.txt", "a", encoding="utf-8") as f:
        for item in data:
            f.write(f"{item}\n")
    print("Data written to file successfully.")


def insert_tweet(tweet,prediction):
    HOST = "localhost"
    PORT = 3307
    USER = "root"
    PASSWORD = "password"
    DB = "railway"
    query=""
    if prediction == 1:
        query = "INSERT INTO emergency(Tweets) VALUES(%s);"
    else:
        query = "INSERT INTO feedback(Tweets) VALUES(%s);"
    
    conn = None
    try:
        conn = MySQLdb.connect(host=HOST, port=PORT, user=USER, passwd=PASSWORD, db=DB)
        cursor = conn.cursor()
        cursor.execute(query, (tweet,))
        
        conn.commit()
    except MySQLdb.Error as e:
        print(e)
        
    finally:
        if conn:
            conn.close()

def process_data(rdd,bc_model):
    if not rdd.isEmpty():
        print("Processing data ...")
        nbModel = bc_model.value
        hashingTF = HashingTF(100000)
        tf = hashingTF.transform(rdd.map(lambda x: x[3].encode('utf-8', 'ignore')))  # assuming 'text' column is the 4th element
        tf.cache()
        idf = IDF(minDocFreq=2).fit(tf)
        tfidf = idf.transform(tf)
        tfidf.cache()
        predictions = nbModel.predict(tfidf)

        results = []
        for (twitterUrl, url, tweet_id, text, createdAt), prediction in zip(rdd.collect(), predictions.collect()):
            if(prediction==0):
                results.append((text,"Feedback"))
                insert_tweet(text,prediction)
            else:
                results.append((text,"Emergency"))
                insert_tweet(text,prediction)
        write_to_file(results)
    else:
        print("Empty RDD !!!")

if __name__ == "__main__":


    # Load pre-trained Naive Bayes model and broadcast it
    conf = SparkConf().setMaster("local[2]").setAppName("Streamer")
    sc = SparkContext(conf=conf)
    sc.setLogLevel("ERROR")

    ssc = StreamingContext(sc, 60)
    ssc.checkpoint("checkpoint")

    with open('IRModel1', 'rb') as f:
        loaded_model = pickle.load(f)

    bc_model = sc.broadcast(loaded_model)

    csv_file_path = "C:/Users/Pratush/Downloads/SDP 2024/data/raw data.csv"
    
    # Create RDD from CSV file
    csv_data = sc.textFile(csv_file_path)
    header = csv_data.first()
    csv_data = csv_data.filter(lambda row: row != header)
    tweet_data = csv_data.map(lambda line: line.split(","))
    tweet_data = tweet_data.map(lambda fields: (fields[0] if len(fields) > 0 else ' ', fields[1] if len(fields) > 1 else ' ', fields[2] if len(fields) > 2 else ' ', fields[3] if len(fields) > 3 else ' ', fields[4] if len(fields) > 4 else ' '))  # Map to (twitterUrl, url, id, text, createdAt)

    # Process the RDD data
    process_data(tweet_data,bc_model)

    print("Processing complete.")
