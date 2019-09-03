
import os

source_folder = 'chunks'
destination_file = 'merged'
no_of_chunks = len(os.listdir('chunks'))

with open(destination_file, 'wb') as image:
        for j in range(1,no_of_chunks+1):
            filename = os.path.join(source_folder, ('{}'.format(j)))
            read_file = open(filename, 'rb')
            for f in read_file:
                image.write(f)
            read_file.close()