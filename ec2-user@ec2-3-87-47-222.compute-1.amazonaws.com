import pymysql
import string
from array import *

def userreport(user, db):

    dbcursor = db.cursor()

    dbcursor.execute('SELECT * FROM stats WHERE username = %s', user)

    result = dbcursor.fetchone()

    if result == None:
        print("User does not exist.")
    else:
        winrate = result[6]*100

        print(str(user)+"'s User Report:")
        print(str(user)+" has played "+str(result[1])+" games; winning "+str(result[2])+" of them, losing "+str(result[4])+" of them, and drawing the other "+str(result[3])+".")
        print("This makes their current win rate " +str(winrate)+"%.")
        print("With wins, draws, and loses being worth 3, 1, and 0 points respectively, "+str(user)+" is currently on a score of "+str(result[5])+".")

def lbreport(db):

    dbcursor = db.cursor()

    dbcursor.execute("SELECT * FROM stats")

    scores = [[]]

    i = 0
    result = mycursor.fetchall()
    for row in result:
        arr = [row[0], row[5], row[6]]
        scores.append(arr)
        i += 1

    scores.sort(key=lambda x: (-x[1], -x[2]))

    print("Leaderboard Report:")
    print(scores)
    return

def gamesreport(db):

    dbcursor = db.cursor()

    dbcursor.execute("SELECT * FROM games")

    result = mycursor.fetchall()

    i = 0
    for row in result:
        i += 1

    print("Games Report:")
    print("There are "+i+" games going on currently.")
    for row in result:
        print(str(row[1])+" is playing "+ str(row[2]) +". It's turn "+str(row[3])+" and it's "+str(row[4])+"'s turn next.")

    return

# def updatereport(db):
#     print("Updating report database to match current database...")
#
#     dbcursor = db.cursor()
#
#     dbcursor.execute("SELECT * FROM stats WHERE username = "+user)
#
#     print("MATCHED")
#
#     return

db = pymysql.connect(
  host="tictactoedata.ckyvtmldqfco.us-east-1.rds.amazonaws.com",
  database="tictactoe",
  user="admin",
  password="password123"
)

print("Reporting Options")
print("  - 1 for a user report")
print("  - 2 for a leaderboard report")
print("  - 3 for a current games report")
# print("  - 5 to update the reporting database")
print("  - an empty input to exit application")

while(True):
    val = str(raw_input("Please enter a number: "))

    if val == "1":
        username = raw_input("Please input the username of the user you'd like a report on: ")
        userreport(username, db)
        break;
    elif val == "2":
        lbreport(db)
        break;
    elif val == "3":
        gamesreport(db)
        break;
    # elif val == "5":
    #     updatereport(db)
    #     break;
    elif val == "":
        break;
    else:
        print("That was not a valid input. Enter blank line to exit.")
