#!/usr/bin/python
import requests
import nmap
import json

def getIP(url,user,password):
	r = requests.get(url, auth=(user, password))
	if r.status_code==403:
		raise Exception("Password o Usuario incorrectos")
	return r.text

def main():
	url="http://192.168.0.11/api/getIP.php"
	user="cr998"
	password="gepagi88"
	i=0
	while True:
		ip=getIP(url,user,password)
		try:
			data=scan(ip)
		except Exception as e:
			data="down"
		i=i+1
		print "Ips escaneadas %s" % (i) 
		dat=[ip,data]
		sendData(json.dumps(dat),user,password)



def scan(ip):
	print "Se escanea la ip-> "+ip
	puertos="80,8080,22,21,1000"
	nm = nmap.PortScanner()
	nm.scan(ip, puertos, arguments='-T5')
	lport = nm[ip]["tcp"].keys()
	return lport

def sendData(data,user,password):
	r = requests.post('http://192.168.0.11/api/setData.php', 
		data = {'data':data},auth=(user, password))
	print r.text

main()
