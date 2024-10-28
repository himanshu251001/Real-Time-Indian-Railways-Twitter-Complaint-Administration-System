import os
import sys
import pickle
# Set the Spark home directory and update the system path
os.chdir(r"C:\Spark")
os.curdir
if 'SPARK_HOME' not in os.environ:
    os.environ['SPARK_HOME'] = r'C:\Spark'

SPARK_HOME = os.environ['SPARK_HOME']

sys.path.insert(0, os.path.join(SPARK_HOME, "python"))
sys.path.insert(0, os.path.join(SPARK_HOME, "python", "lib"))
sys.path.insert(0, os.path.join(SPARK_HOME, "python", "lib", "pyspark.zip"))
sys.path.insert(0, os.path.join(SPARK_HOME, "python", "lib", "py4j-0.10.4-src.zip"))

# Specify the correct Python executable
os.environ['PYSPARK_PYTHON'] = r'C:\Users\Pratush\AppData\Local\Programs\Python\Python310\python.exe'

from pyspark import SparkContext, SparkConf
from pyspark.mllib.feature import HashingTF, IDF
from pyspark.mllib.regression import LabeledPoint
from pyspark.mllib.classification import NaiveBayes, NaiveBayesModel
from pyspark.sql import SQLContext
import operator

# Initialize Spark
conf = SparkConf()
conf.set("spark.executor.memory", "1g")
conf.set("spark.cores.max", "2")

conf.setAppName("IRApp")

sc = SparkContext('local', conf=conf)



# Corrected path to the data file
tweetData = sc.textFile("C:/Users/Pratush/Downloads/SDP 2024/data/tweets_formatted_data.csv")
tweetData.take(2)
# Process data
fields = tweetData.map(lambda x: x.split(","))
tweetData.take(1)
documents = fields.map(lambda x: x[1].lower().split(" "))
tweetData.take(1)
documentNames = fields.map(lambda x: x[0])

# Feature extraction with TF-IDF
hashingTF = HashingTF(100000)
article_hash_value = hashingTF.transform(documents)
article_hash_value.cache()

idf = IDF().fit(article_hash_value)
tfidf = idf.transform(article_hash_value)

xformedData = tweetData.zip(tfidf)
xformedData.cache()
xformedData.collect()[0]

def convertToLabeledPoint(inVal):
    origAttr = inVal[0].split(",")
    sentiment = 0.0 if origAttr[0] == "feedback" else 1.0
    return LabeledPoint(sentiment, inVal[1])

tweetLp = xformedData.map(convertToLabeledPoint)
tweetLp.cache()
tweetLp.collect()

# Train Naive Bayes model
model = NaiveBayes.train(tweetLp, 1.0)
predictionAndLabel = tweetLp.map(lambda p: (float(model.predict(p.features)), float(p.label)))
predictionAndLabel.collect()
# Form confusion matrix
sqlContext = SQLContext(sc)
predDF = sqlContext.createDataFrame(predictionAndLabel.collect(), ["prediction", "label"])
predDF.groupBy("label", "prediction").count().show()




# Save the model
model_save_path = 'C:/Users/Pratush/Downloads/SDP 2024/IRModel1'
with open(model_save_path, 'wb') as model_file:
    pickle.dump(model, model_file)



