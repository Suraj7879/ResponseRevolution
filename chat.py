#!/usr/bin/env python3
import openai

# Set your secret API key
openai.api_key_path = "api_key.txt"

while(True):
    print("What is your question?")
    prompt = input("You: ")
    response = openai.Completion.create(
        engine="text-davinci-003",
        prompt=prompt,
        temperature=0.9,
        max_tokens=1000,
        top_p=1,
        frequency_penalty=0,
        presence_penalty=0,
    )

    # Format the response
    response_format = response.choices[0].text.replace("\\n", "\n")

    # Print the response
    print("Chatbot: " + response_format)