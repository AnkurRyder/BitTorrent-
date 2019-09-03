# first of all import the socket library 
import socket		
import pickle	 

# next create a socket object 
s = socket.socket()		 
print ("Socket successfully created")

# reserve a port on your computer in our 
# case it is 12345 but it can be anything 
port = int(input('Enter the port to bind to '))				

# Next bind to the port 
# we have not typed any ip in the ip field 
# instead we have inputted an empty string 
# this makes the server listen to requests 
# coming from other computers on the network 
s.bind(('', port))		 
print ( "socket binded to %s" %(port) )

# put the socket into listening mode 
s.listen(5)	 
print ( "socket is listening"	)		

# a forever loop until we interrupt it or 
# an error occurs
ls = [] 
while True: 

# Establish connection with client. 
    c, addr = s.accept()
    port = c.recv(1024)
    port = port.decode('utf-8')

    addr = list(addr)
    addr[1] = int(port)

    if addr not in ls:
        ls.append(addr)

    print ('Got connection from', addr )

    mess =pickle.dumps(ls)
    # mess = mess.encode('utf-8')
    c.send(mess) 

    # Close the connection with the client 
    c.close() 
    print ('Replied Succesfully')
