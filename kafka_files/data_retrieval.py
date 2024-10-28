from kafka import KafkaProducer
import json
from apify_client import ApifyClient
import time


# Initialize the ApifyClient with your API token

client = ApifyClient("apify_api_kThqNIN6Y6R4Dg7oR1VwNRk6r1qoSX0DUAnY")


# Run the Actor task and wait for it to finish

run = client.task("NIXerztZTjLM5xNM4").call()

producer = KafkaProducer(bootstrap_servers='localhost:9092')


# Fetch and print Actor task results from the run's dataset (if there are any)
for item in client.dataset(run["defaultDatasetId"]).iterate_items():
    full_text = item.get("full_text","")
    print(" \n "+full_text + " \n ")
    message_value = json.dumps(item).encode('utf-8')
    producer.send("twitterstream", value=message_value)
    time.sleep(1)






