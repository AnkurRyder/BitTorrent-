import sys
import os

ls = sys.argv[1:]

image_file = ls[0]

    # This file is for dev purposes. Each line is one piece of the message being sent individually
    #chunk_file = open('chunkfile.txt', 'wb+')
i=1
with open(image_file, 'rb') as infile:
    while True:
        # Read 512 kbyte chunks of the image
        chunk = infile.read(256000)
        if not chunk: break

        # Do what you want with each chunk (in dev, write line to file)

        if not os.path.exists(ls[1]):
            os.makedirs(ls[1])
        filename = os.path.join(ls[1], ('{}'.format(i)))
        chunk_file = open(filename, 'wb+')
        chunk_file.write(chunk)
        chunk_file.close()

        if not os.path.exists('backup'):
            os.makedirs('backup')
        filename = os.path.join('backup', ('{}'.format(i)))
        chunk_file = open(filename, 'wb+')
        chunk_file.write(chunk)
        chunk_file.close()

        i+=1
