# Quiz App Documentation

## Overview
The Quiz App is a web-based application designed to allow users to take quizzes on various topics. The application is built using PHP and MySQL, with quiz questions stored in JSON files.

> This project has been made by BYT-Bender / Sourabh Srivastva

## Features
 - **User Authentication:** Users must log in to take quizzes.
 - **Category Selection:** Users can filter quizzes by category.
 - **Timer Functionality:** Optionally set a timer for each quiz, which automatically submits the quiz when time expires.
 - **Question Navigation:** Users can navigate between questions using "Previous" and "Save and Next" buttons.
 - **Final Result:** The quiz result is displayed at the end, showing the score.
 - **Leaderboard:** Displays the top scores with usernames, points, and ranks.

## Application Flow
1. Home Screen:
   - Displays a welcome message with the user's name and a list of quiz categories.
   - Users select a category to view available quizzes or search for a quiz.

2. Quiz Selector:
   - Lists quizzes filtered by the selected category.
   - Displays quiz details.

3. Quiz Interface:
   - Displays questions one at a time.
   - Users can navigate using the "Previous" and "Save and Next" buttons.
   - On the last question, the "Save and Next" button changes to "Submit".

4. Quiz Completion:
   - The user's answers are stored in the temp_answers table during the quiz.
   - Upon submission, the answers are moved to the scores table, and the quiz result is displayed.

5. Leaderboard:
   - Displays the top users based on quiz scores, showing username, points, and rank.


## Preview

You can create your account
![Screenshot 2024-08-19 201835](https://github.com/user-attachments/assets/bc11571d-0489-4935-b69b-0a878d3e82ea)

Login with it
![Screenshot 2024-08-19 201935](https://github.com/user-attachments/assets/da332d0b-9dd7-4843-b92b-044ede96ce45)

This is the dashboard of Quiz App
![Screenshot 2024-08-19 202018](https://github.com/user-attachments/assets/ebc8ff22-b0ac-43ad-a12d-03762523bc77)

You can either search for quiz using search bar
![Screenshot 2024-08-19 202059](https://github.com/user-attachments/assets/b524544b-b638-4f46-bff0-16aa9a90027e)

Or select a category to filter quizzes
![Screenshot 2024-08-19 202135](https://github.com/user-attachments/assets/05787827-1e7b-42b5-a212-53de68254638)

You can select a quiz by click on it
![Screenshot 2024-08-19 202226](https://github.com/user-attachments/assets/0a31d61c-418d-4fd2-9a18-c13063a88f0d)

Next page will give you deatailed view of the quiz
![Screenshot 2024-08-19 202300](https://github.com/user-attachments/assets/4f35d3aa-63fc-48e7-884d-ad1f0f55be06)

After clickin on "Start Quiz" you can attempt the questions
![Screenshot 2024-08-19 202332](https://github.com/user-attachments/assets/c51a36c7-e5d3-47a5-be43-9d03bf149374)

Simply select a option and click "Save & Next" [Green]
![Screenshot 2024-08-19 202407](https://github.com/user-attachments/assets/9b205012-25ac-483e-a58e-1b9efec59876)

You can also makr a question for review [Blue]
![Screenshot 2024-08-19 202443](https://github.com/user-attachments/assets/899d0155-20b2-4c5b-9726-e9aa641bcd69)

And if you decide to not attempt the question it will be marks as not attempted [Red]
![Screenshot 2024-08-19 202519](https://github.com/user-attachments/assets/21de65dd-288f-4c3a-b3c5-4964185778e6)

You can also click "Clear Response" to unselect any option
![Screenshot 2024-08-19 202624](https://github.com/user-attachments/assets/d7fd6f31-122c-4e49-9668-a4a59b1b37af)

You can also click "Previous" to go back to the previous question
![Screenshot 2024-08-19 202653](https://github.com/user-attachments/assets/d8cb3ba2-1971-4000-b0cc-ff320d0fe2bd)

When on the last question the "Save & Next" button changes into "Submit" button
![Screenshot 2024-08-19 202748](https://github.com/user-attachments/assets/67dc1b3a-e099-4753-a0a4-435a9bb8571a)

You can submit the quiz by clicking on the "Submit" button or let the timer run out and it will automatically be submitted
[![Watch the video]([https://i.sstatic.net/Vp2cE.png)](https://youtu.be/vt5fpE0bzSY](https://github.com/user-attachments/assets/1977b2dc-5610-4612-9437-6b1ded2f4293))

You can click on "Ranking" to see your rank
![Screenshot 2024-08-19 203704](https://github.com/user-attachments/assets/e7f65f74-0baf-43ee-8199-799a193195bd)
![Screenshot 2024-08-19 203732](https://github.com/user-attachments/assets/0dc8a01b-c829-498f-8fcc-61adf6ce8426)

and click "Points" to see you quiz history and how much points you gained from each quiz
![Screenshot 2024-08-19 203811](https://github.com/user-attachments/assets/647d166f-584a-4a93-b8f2-9a13110fc7cc)

At the end you can logout from the session
![Screenshot 2024-08-19 204047](https://github.com/user-attachments/assets/8cfedee5-f6b1-409a-8314-a652a272d86f)















