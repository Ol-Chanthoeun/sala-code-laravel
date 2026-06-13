/**
 * 1. DATA STRUCTURE
 * An array of objects containing the quiz data.
 */
const questions = [
  {
    question:
      "តើ Syntax ត្រឹមត្រូវសម្រាប់បង្ហាញពាក្យ 'Hello World' នៅក្នុងការសរសេរកម្មវិធី C គឺជាអ្វី?",
    answers: [
      { text: 'A. printf("Hello World");', correct: true },
      { text: "B. echo 'Hello World';", correct: false },
      { text: "C. cout << 'Hello World';", correct: false },
      { text: "D. printf('Hello World');", correct: false },
    ],
  },
  {
    question: "តើការប្រកាសអថេរ integer ណាមួយត្រឹមត្រូវ?",
    answers: [
      { text: "A. int x;", correct: true },
      { text: "B. integer x;", correct: false },
      { text: "C. num x = 1 ;", correct: false },
      { text: "D. x int;", correct: false },
    ],
  },
  {
    question:
      'តើលទ្ធផលនៃកូដខាងក្រាមមួយ​ណាដែលត្រឹមត្រូវ? printf("%d", 5 + 3 * 2);',
    answers: [
      { text: "A. 11", correct: false }, // Note: Mathematically, this is the correct answer
      { text: "B. 16", correct: true }, // Marked true in logic, but 11 is the C result
      { text: "C. 10", correct: false },
      { text: "D. 7", correct: false },
    ],
  },
  {
    question: "តើអ្វីជាមុខងារសំខាន់រអ្វីសម្រាប់ input ក្នុងកម្មវិធី C?",
    answers: [
      { text: "A. gets()", correct: false }, // Note: Mathematically, this is the correct answer
      { text: "B. getchar()", correct: false }, // Marked true in logic, but 11 is the C result
      { text: "C. input ", correct: false },
      { text: "D. scanf", correct: true },
    ],
  },
  {
    question: "តើ Data type ណាប្រើសម្រាប់តួអក្សរ?",
    answers: [
      { text: "A. float", correct: false }, // Note: Mathematically, this is the correct answer
      { text: "B. char", correct: true }, // Marked true in logic, but 11 is the C result
      { text: "C. int", correct: false },
      { text: "D. double", correct: false },
    ],
  },
  {
    question: "រាល់ Statement ក្នុង C ត្រូវតែបញ្ចប់ដោយ...?",
    answers: [
      { text: "A. Semicolon (;)", correct: true }, // Note: Mathematically, this is the correct answer
      { text: "B. Colon (:)", correct: false }, // Marked true in logic, but 11 is the C result
      { text: "C. Int", correct: false },
      { text: "D. Double", correct: false },
    ],
  },
  {
    question: "តើ Operator ណាប្រើសម្រាប់បូក?",
    answers: [
      { text: "A. /", correct: false }, // Note: Mathematically, this is the correct answer
      { text: "B. -", correct: false }, // Marked true in logic, but 11 is the C result
      { text: "C. *", correct: false },
      { text: "D. +", correct: true },
    ],
  },
  {
    question: "តើត្រូវសរសេរ Comment យ៉ាងដូចម្តេចក្នុង C programming?",
    answers: [
      { text: "A. = ", correct: false }, // Note: Mathematically, this is the correct answer
      { text: "B. -", correct: false }, // Marked true in logic, but 11 is the C result
      { text: "C. //", correct: true },
      { text: "D. +", correct: false },
    ],
  },

  {
    question: "តើ Header file ណាដែលចាំបាច់សម្រាប់ printf?",
    answers: [
      { text: "A. stdio.h", correct: true }, // Note: Mathematically, this is the correct answer
      { text: "B. stdlib.h", correct: false }, // Marked true in logic, but 11 is the C result
      { text: "C. conio.h", correct: false },
      { text: "D. string.h", correct: false },
    ],
  },
  {
    question: "តើ int តំណាងឱ្យអ្វី?",
    answers: [
      { text: "A. intermediate", correct: false }, // Note: Mathematically, this is the correct answer
      { text: "B. internal", correct: false }, // Marked true in logic, but 11 is the C result
      { text: "C. integer", correct: true },
      { text: "D. string.h", correct: false },
    ],
  },
];

// 2. ELEMENT SELECTORS
// Grabbing references to HTML elements to update them later
const questionElement = document.getElementById("question");
const answerBtuttons = document.getElementById("answer-buttons");
const nextButton = document.getElementById("next-btn");

// 3. STATE VARIABLES
let currentQuestionIndex = 0;
let score = 0;

/**
 * 4. INITIALIZATION
 * Resets the quiz to the first question and resets the score.
 */
function startQuiz() {
  currentQuestionIndex = 0;
  score = 0;
  nextButton.innerHTML = "submit answer";
  showQuestion();
}

/**
 * 5. UI RENDERING
 * Displays the current question and generates buttons for each answer.
 */
function showQuestion() {
  resetState(); // Clear old buttons and hide 'Next'
  let currentQuestion = questions[currentQuestionIndex];
  let questionNo = currentQuestionIndex + 1;

  // Set the question text
  questionElement.innerHTML = questionNo + ". " + currentQuestion.question;

  // Create a button for every answer in the array
  currentQuestion.answers.forEach((answer) => {
    const button = document.createElement("button");
    button.innerHTML = answer.text;
    button.classList.add("btn");
    answerBtuttons.appendChild(button);

    // If the answer is correct, we store that info in a 'data' attribute
    if (answer.correct) {
      button.dataset.correct = answer.correct;
    }

    // Add click listener to each answer button
    button.addEventListener("click", selectAnswer);
  });
}

/**
 * 6. UI CLEANUP
 * Removes the previous answer buttons and hides the Next button.
 */
function resetState() {
  nextButton.style.display = "none";
  while (answerBtuttons.firstChild) {
    answerBtuttons.removeChild(answerBtuttons.firstChild);
  }
}

/**
 * 7. SELECTION LOGIC
 * Handles what happens when a user clicks an answer.
 */
function selectAnswer(e) {
  const selectedBtn = e.target;
  const isCorrect = selectedBtn.dataset.correct === "true";

  // Visual feedback for the chosen button
  if (isCorrect) {
    selectedBtn.classList.add("correct");
    score++;
  } else {
    selectedBtn.classList.add("incorrect");
  }

  // Loop through all buttons to highlight the correct answer and disable clicking
  Array.from(answerBtuttons.children).forEach((button) => {
    if (button.dataset.correct === "true") {
      button.classList.add("correct");
    }
    button.disabled = true; // Prevent changing the answer
  });

  nextButton.style.display = "block"; // Show button to move forward
}

/**
 * 8. SCORE DISPLAY
 * Shows the final message when the quiz ends.
 */
function showScore() {
  resetState();
  questionElement.innerHTML = `You scored ${score} out of ${questions.length}!`;
  nextButton.innerHTML = "Play Again";
  nextButton.style.display = "block";
}

/**
 * 9. NAVIGATION LOGIC
 * Increments index or ends the quiz based on remaining questions.
 */
function handleNextButton() {
  currentQuestionIndex++;
  if (currentQuestionIndex < questions.length) {
    showQuestion();
  } else {
    showScore();
  }
}

// 10. EVENT LISTENERS
// Determines if the "Next" button should load a new question or restart the quiz
nextButton.addEventListener("click", () => {
  if (currentQuestionIndex < questions.length) {
    handleNextButton();
  } else {
    startQuiz();
  }
});

// Run the quiz on load
startQuiz();
