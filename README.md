> This project has been made by BYT-Bender / Sourabh Srivastva

# Quiz App Documentation

## Overview
The Quiz App is a web-based application designed to allow users to take quizzes on various topics. The application is built using PHP and MySQL, with quiz questions stored in JSON files.

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
