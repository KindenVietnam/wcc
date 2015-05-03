#/usr/bin/python
# import module to access Microsoft SQL Server
import pymssql
import psycopg2
import sys
import datetime
# set parameters
# mssql server connection information
ivs_host = '172.16.10.200\sqlexpress2008r2'
ivs_user = 'att'
ivs_password = '$Rgh789'
ivs_database = 'BZ_Kinden'
# psql server connection information
pg_host = 'localhost'
pg_user = 'postgres'
pg_password = 'danghungit'
pg_database = 'ivs'
# set up connection string
# get a connection to ivs mssql
conn_ivs = pymssql.connect(host=ivs_host, user=ivs_user, password=ivs_password,database=ivs_database, as_dict=True)
# get a connection to local postgresql server
conn_pg = psycopg2.connect(host=pg_host, user=pg_user, password=pg_password, dbname=pg_database)
# return a cursor object from a connection
cursor_ivs = conn_ivs.cursor()
cursor_pg = conn_pg.cursor()
# -----------------------------------------------
# load staff finger data to postgres database
# -----------------------------------------------
def load_staff_data():
	cursor_ivs.execute("SELECT SoThe FROM trangthaichamcong")	
	rows_staff = cursor_ivs.fetchall()
	for row in rows_staff:
		#print "%s" % row['sothe']
		cursor_pg.execute("INSERT INTO temp_staff(staff_id) VALUES(%s)",row['SoThe'])
	conn_pg.commit()
	cursor_pg.execute("""INSERT INTO staff(staff_id) (SELECT staff_id FROM temp_staff WHERE staff_id not in (SELECT staff_id FROM staff))""")
	cursor_pg.execute("DELETE FROM temp_staff")
	conn_pg.commit()
	print "Update new staffs completed!"
# ------------------------------------------------
# load machine data
# ------------------------------------------------
def load_machine_data():
	cursor_ivs.execute("SELECT * FROM Machines")
	rows_machine = cursor_ivs.fetchall()
	for row in rows_machine:
		# print "%s" % row.ID,row.MachineAlias
	    # print "%s" % row['ID'],row['MachineAlias']
		cursor_pg.execute("INSERT INTO temp_machine(machine_no, name) VALUES(%s, %s)", (row['MachineNumber'], row['MachineAlias']))
	conn_pg.commit()
 	cursor_pg.execute("""INSERT INTO machine(machine_no, name) (SELECT machine_no, name FROM temp_machine WHERE machine_no NOT IN (SELECT machine_no FROM machine))""")
	cursor_pg.execute("DELETE FROM temp_machine")
	conn_pg.commit()
	print "Update new machines completed!"
# --------------------------------------------------
# load finger data from MSSQL to Postgresql
# --------------------------------------------------
def load_att_data():
	#cursor_pg.execute("DELETE FROM inout")
	#conn_pg.commit()
	cursor_pg.execute("select max(inout_id) as solonnhat from inout")
	row_id = cursor_pg.fetchall()
	print row_id['solonnhat']
	#cursor_ivs.execute("select id,sothe,thoigian from trangthaichamcong")
	#rows_att = cursor_ivs.fetchall()
	#for row in rows_att:
		#print "%s" % row['id'],row['sothe'],row['thoigian']
		#print "%s" % row['staff_id'],row['machine_no'],row['checktime']
		#cursor_pg.execute("INSERT INTO temp_inout(id,staff_id,checktime) VALUES(%s, %s, %s)", (row['id'],row['sothe'],row['thoigian']))
	#conn_pg.commit()
	#cursor_pg.execute("""INSERT INTO inout(inout_id, staff_id, checktime) (SELECT id,staff_id,checktime FROM temp_inout WHERE id not in(SELECT inout_id FROM inout))""")
	#cursor_pg.execute("DELETE FROM temp_inout")
	#conn_pg.commit()
	#print "Load finger data completed!"
# start here the loader
# update machine if any
#load_machine_data()
# update staff information
#load_staff_data()
# download attendance information
load_att_data()
# close connection
print "Closing connection to database .."
conn_pg.close()
conn_ivs.close()
