email = input("Enter email: ")

foundAt = -1
foundDot = -1

for i in range(0, len(email)):
    print(email[i])
    if(email[i] == "@"):
        foundAt = i
    if(email[i] == "."):
        foundDot = i

if (foundAt >=1 and foundDot > foundAt +1 and foundDot < len(email) -2): 
    print("Valid email")
else:
    print("Invalid email")

    

