import socket 
import threading
from time import sleep
import os
import json
import sys
from shutil import copyfile
from random import shuffle
from threading import Lock, Thread
import pickle

lock = Lock()
have = os.listdir('chunks')

def store(mess):
        copyfile('backup/{}'.format(mess),'chunks/{}'.format(mess))


def new_connection(c,addr):
    print ('Got connection from', addr )  
    i = 1
    while True:
            #file_name = c.recv(1024)
            mess = c.recv(1024)
            if mess == b'' : break
            mess = mess.decode('utf-8')
            print("recieved: {}".format(mess))
            lock.acquire()
            if mess in have:
                    print('.')
                    lock.release()
            
            else:  
                    have.append(mess)
                    lock.release()
                    print('Recieved chunk{} from '.format(mess), addr)
                    store(mess)

                    
                    

    print('connection closed')
    # Close the connection with the client 
    c.close() 

def serve(s):
                   
    while True: 
        
        # Establish connection with client. 
        print ("socket is listening" ) 
        c, addr = s.accept()
        c.setblocking(True)
        t = threading.Thread(target=new_connection, args=(c,addr))
        t.start()      
        

def connect(ip,port):
    
    s = socket.socket()	
    s.setblocking(True) 	 			
    s.connect((ip, port)) 
    i = 1
    ls = os.listdir('chunks')
    ls.sort(key=int)
    shuffle(ls)
    

    for filename in ls:
        # file = open("chunks/{}".format(filename),"rb")
        # message = file.read()
        filename = filename.encode('utf-8')
        # # s.send(name)
        s.send(filename)
        print("sending {}".format(filename.decode('utf-8')))
        sleep(0.25)
        i+=1
 
 
    s.close()	 

def connect_to_tracker(ip,port):
        t = socket.socket()		 
        port = int( port )				
        t.connect((ip, port)) 
        t.send(str(serving_port).encode('utf-8'))
        data = t.recv(1024) 
        list_of_peers = pickle.loads(data)
        # print(list_of_peers)
        t.close()
        return list_of_peers

######Main########

print('Serving....')    
print('Enter the port to serve on:')
serving_port = int(input())
s = socket.socket()    
s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)   
s.setblocking(True)   
print ("Socket successfully created")
s.bind(('', serving_port))         
print ("socket binded to %s" %(serving_port) )
s.listen(5)


print('Connecting to the traker...')
print('Enter the IP Adress of the tracker')
tracker_ip = input()
tracker_port  = input("Enter the port number of the tracker ")
list_of_peers = connect_to_tracker(tracker_ip,tracker_port)	 


t1 = threading.Thread(target=serve, args=(s,)) 
t1.start()

ch = input()

list_of_peers = connect_to_tracker(tracker_ip,tracker_port)
me = ['127.0.0.1',serving_port]

for peer in list_of_peers:
        if me != peer :
                t2 = threading.Thread(target=connect, args=(peer[0],int(peer[1]),))
                t2.start()



t1.join()
t2.join()


