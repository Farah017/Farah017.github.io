import time
print("Welcome to the BMI Calculator") #weight/height**2
print("\n")
time.sleep(1)

weight = float(input("Enter weight(kg): "))
print("\n")
time.sleep(1)

height = float(input("Enter height(m): "))
print("\n")
time.sleep(1)

BMI = weight/(height**2)
print("BMI", BMI )
print("\n")


if BMI < 18.5  : 
    print("You are underweight")

if BMI >= 18.5 and BMI <=25.0 :
    print("You are healthy")

if BMI > 25.0 and BMI < 29.9 :
    print("You are overweight")

if BMI > 30:
    print("You are morbidly obese")
