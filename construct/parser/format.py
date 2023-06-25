from json import load, dumps
from colorama import Fore, Style


def print_question(test, number):
    print(str(number), ".\nQuestion: ", sep='', end='')

    try:
        print(test[str(number)][0], end='')
    except:
        print(Fore.RED + "NONE!!!")
        print(Fore.RESET)


    print("\nAnswer: ", end='')

    try:
        print(test[str(number)][1], end='')
    except:
        print(Fore.RED + 'NONE!!!')
        print(Fore.RESET)

    print("\n")


def print_test(test):
    print("\n")
    for i in range(1, len(test) + 1):

        print_question(test, i)

    print('\n')


f1 = open("unformatted_data.json")
unformatted_data = load(f1)
f1.close()


f2 = open("themes.json")
themes = load(f2)
f2.close()

tests = []
excluded = {}
tasks = []
all_themes = []

for unformatted_item in unformatted_data.items():
    filename = unformatted_item[0]
    unformatted_test = unformatted_item[1]
    formatted_test = []
    formatted_themes = []
    print(Fore.MAGENTA)
    print(filename, '\n')
    print(Fore.RESET)
    print("Here is an example:\nQuestion: ", unformatted_test["3"][0], "\nAnswer: ", unformatted_test["3"][1], "\n\nChoose the question type:\n1 - One blank field with different possible answers.\n2 - Several blank fields with one answer variant.\n3 - Do not include this test\n", sep='')
    question_type = int(input("Enter 1 or 2 or 3: "))
    if question_type != 3:
        print_test(unformatted_test)
        ok = int(input("Is this test correct? 0 - no, 1 - yes: "))
        while ok != 1:
            incorrect_number = input("Enter the question number, which is incorrect: ")
            print_question(unformatted_test, incorrect_number)
            incorrect_option = input("Enter the option:\n1 - Change question.\n2 - Change answer.\n\nLeave the field blank to go back: ")
            while incorrect_option == "1" or incorrect_option == "2":

                if incorrect_option == "1":
                    question = input("Enter new question: ")
                    try:
                        unformatted_test[incorrect_number][0] = question
                    except:
                        unformatted_test[incorrect_number].append(question)

                elif incorrect_option == "2":
                    answer = input("Enter new answer: ")
                    try:
                        unformatted_test[incorrect_number][1] = answer
                    except:
                        unformatted_test[incorrect_number].append(answer)

                print_question(unformatted_test, incorrect_number)
                incorrect_option = input("Enter the option:\n1 - Change question.\n2 - Change answer.\n\nLeave the field blank to go back: ")

            print_test(unformatted_test)
            ok = int(input("Is this test correct? 0 - no, 1 - yes: "))

        task = input("\nEnter the task / question: ")
        for i in range(len(themes)):
            print(i + 1, '. ', themes[i], sep='')

        theme = input("Choose theme (0 - without theme): ")
        if theme.isnumeric():
            theme = int(theme) - 1
        else:
            theme = -1

        for i in range(1, len(unformatted_test) + 1):
            question_array = [unformatted_test[str(i)][0], 1, -1, [unformatted_test[str(i)][0].replace(' ', '').upper()], "", None]
            if question_type == 1:
                question_array[4] = "definite"
            elif question_type == 2:
                question_array[4] = "missing_words"
            formatted_test.append(question_array)
            formatted_themes.append(theme)

        tests.append(formatted_test)
        tasks.append(task)
        all_themes.append(formatted_themes)
    else:
        excluded[filename] = unformatted_test

    print('\n' + Fore.GREEN + 'SUCCESS!\n' + Fore.RESET)

f3 = open("excluded.json", "r+")
f3.write(dumps(excluded))
f3.close()

f4 = open("formatted_themes.json", "r+")
f4.write(dumps(all_themes))
f4.close()

f5 = open("formatted_tasks.json", "r+")
f5.write(dumps(tasks))
f5.close()

f6 = open("data.json", "r+")
f6.write(dumps(tests))
f6.close()
