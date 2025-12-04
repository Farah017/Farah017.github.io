import time

import easygui as eg

scoretally = 0

# Welcome Message
eg.msgbox("Welcome to the Digital Environment quiz!", title="Quiz")

# Introduction
eg.msgbox("There are 10 true or false questions in this quiz.", title="Instructions")
time.sleep(1)

# Question 1
Q1 = eg.buttonbox("Q1: True or false, a keyboard is an input device?", choices=["True", "False"], title="Question 1")
if Q1 == "True":
    eg.msgbox("Correct!", title="Result")
    scoretally += 1
else:
    eg.msgbox("Incorrect. Revise input devices.", title="Result")

time.sleep(1)

# Question 2
Q2 = eg.buttonbox("Q2: True or false, a projector is an input device?", choices=["True", "False"], title="Question 2")
if Q2 == "False":
    eg.msgbox("Correct!", title="Result")
    scoretally += 1
else:
    eg.msgbox("Incorrect. Revise input devices.", title="Result")

time.sleep(1)

# Question 3
Q3 = eg.buttonbox("Q3: True or false, a touchscreen is only an output device?", choices=["True", "False"], title="Question 3")
if Q3 == "False":
    eg.msgbox("Correct!", title="Result")
    scoretally += 1
else:
    eg.msgbox("Incorrect. Revise output devices.", title="Result")

time.sleep(1)

# Question 4
Q4 = eg.buttonbox("Q4: True or false, a phone is a computer system?", choices=["True", "False"], title="Question 4")
if Q4 == "True":
    eg.msgbox("Correct!", title="Result")
    scoretally += 1
else:
    eg.msgbox("Incorrect. Revise computer systems.", title="Result")

time.sleep(1)

# Question 5
Q5 = eg.buttonbox("Q5: True or false, HDD is the most expensive type of memory storage?", choices=["True", "False"], title="Question 5")
if Q5 == "False":
    eg.msgbox("Correct!", title="Result")
    scoretally += 1
else:
    eg.msgbox("Incorrect. Revise storage devices.", title="Result")

time.sleep(1)

# Question 6
Q6 = eg.buttonbox("Q6: True or false, RAM is volatile?", choices=["True", "False"], title="Question 6")
if Q6 == "True":
    eg.msgbox("Correct!", title="Result")
    scoretally += 1
else:
    eg.msgbox("Incorrect. Revise properties of RAM.", title="Result")

time.sleep(1)

# Question 7
Q7 = eg.buttonbox("Q7: True or false, virtual memory is fast?", choices=["True", "False"], title="Question 7")
if Q7 == "False":
    eg.msgbox("Correct!", title="Result")
    scoretally += 1
else:
    eg.msgbox("Incorrect. Revise virtual memory.", title="Result")

time.sleep(1)

# Question 8
Q8 = eg.buttonbox("Q8: True or false, ROM stores bootup instructions?", choices=["True", "False"], title="Question 8")
if Q8 == "True":
    eg.msgbox("Correct!", title="Result")
    scoretally += 1
else:
    eg.msgbox("Incorrect. Revise the definition of ROM.", title="Result")

time.sleep(1)

# Question 9
Q9 = eg.buttonbox("Q9: True or false, secondary storage is non-volatile?", choices=["True", "False"], title="Question 9")
if Q9 == "True":
    eg.msgbox("Correct!", title="Result")
    scoretally += 1
else:
    eg.msgbox("Incorrect. Revise secondary storage devices.", title="Result")

time.sleep(1)

# Question 10
Q10 = eg.buttonbox("Q10: True or false, optical disks are expensive to purchase?", choices=["True", "False"], title="Question 10")
if Q10 == "False":
    eg.msgbox("Correct!", title="Result")
    scoretally += 1
else:
    eg.msgbox("Incorrect. Revise optical disks.", title="Result")

# Final Score
eg.msgbox(f"Your score is {scoretally}.", title="Score")

if scoretally >= 6:
    eg.msgbox("You have passed! Have a go at this challenge question.", title="Challenge")
    Q13 = eg.enterbox("Q13: What is CPU cache?")
    
    if Q13 == "a temporary fast memory area that stores frequently used data":
        eg.msgbox("Correct!", title="Challenge Result")
    else:
        eg.msgbox("Incorrect! The correct answer is: 'a temporary fast memory area that stores frequently used data'", title="Challenge Result")
else:
    eg.msgbox("You have FAILED. Use Tech-ICT.com to revise.", title="Failure")