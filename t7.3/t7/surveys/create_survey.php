<?php
// **IMPORTANT:  session_start() MUST be the very first thing in your PHP file, before any HTML or other output!**
session_start();

// Database connection using PDO
$servername = "localhost"; // Change if necessary
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "task_buddy_db"; // Updated database name

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to handle the creation of a new survey
function createSurvey($conn, $title, $description, $reward, $questions) {
    // 1. Insert the survey into the surveys table
    $insertSurveyQuery = "INSERT INTO surveys (title, description, reward, created_by, created_at) VALUES (:title, :description, :reward, :created_by, NOW())";
    $stmt = $conn->prepare($insertSurveyQuery);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':reward', $reward);
    $stmt->bindParam(':created_by', $_SESSION['user_id']); //  Use the user ID from the session
    $stmt->execute();
    $surveyId = $conn->lastInsertId(); // Get the ID of the newly inserted survey

    // 2. Insert the questions
    foreach ($questions as $questionData) {
        $insertQuestionQuery = "INSERT INTO questions (survey_id, question_text, question_type, correct_answer, order_num) VALUES (:survey_id, :question_text, :question_type, :correct_answer, :order_num)";
        $stmt = $conn->prepare($insertQuestionQuery);
        $stmt->bindParam(':survey_id', $surveyId);
        $stmt->bindParam(':question_text', $questionData['text']);
        $stmt->bindParam(':question_type', $questionData['type']);
        $stmt->bindParam(':correct_answer', $questionData['correct_answer']);
        $stmt->bindParam(':order_num', $questionData['order_num']);
        $stmt->execute();
        $questionId = $conn->lastInsertId();

        if ($questionData['type'] === 'multiple choice' && isset($questionData['options'])) {
            foreach ($questionData['options'] as $optionData) {
                $insertOptionQuery = "INSERT INTO options (question_id, option_text, is_correct) VALUES (:question_id, :option_text, :is_correct)";
                $stmt = $conn->prepare($insertOptionQuery);
                $stmt->bindParam(':question_id', $questionId);
                $stmt->bindParam(':option_text', $optionData['option_text']);
                $stmt->bindParam(':is_correct', $optionData['is_correct']);
                $stmt->execute();
            }
        }
    }
    return true;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $reward = $_POST['reward'];
    $questions = $_POST['questions'];

    // Basic validation
    if (empty($title) || empty($reward) || empty($questions)) {
        echo "<script>alert('Please fill in all required fields.'); window.location.href='create_survey.php';</script>";
        exit;
    }

    if (createSurvey($conn, $title, $description, $reward, $questions)) {
       echo "<script>alert('Survey created successfully!'); window.location.href='manage_surveys.php';</script>"; //redirect
        exit;
    } else {
        echo "<script>alert('Failed to create survey.'); window.location.href='create_survey.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Survey - Task Buddy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .question-card {
            transition: all 0.3s ease;
        }
        .question-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="bg-blue-800 text-white w-64 p-6 hidden md:flex flex-col">
            <div class="logo text-2xl font-bold mb-8">
                <span class="text-yellow-400">Task</span>Buddy
            </div>
            <nav class="flex-1">
                <ul class="space-y-3">
                    <li>
                        <a href="../Admin/admindashboard.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="../Admin/manage_users.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-users"></i>
                            <span>Manage Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="create_survey.php" class="flex items-center gap-3 p-3 rounded-lg bg-blue-700 text-white rounded-lg transition-colors">
                            <i class="fas fa-plus-circle"></i>
                            <span>Create Survey</span>
                        </a>
                    </li>
                    <li>
                        <a href="manage_surveys.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-poll"></i>
                            <span>Manage Surveys</span>
                        </a>
                    </li>
                    <li>
                        <a href="../Admin/admin_withdrawals.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Withdrawals</span>
                        </a>
                    </li>
                    <li>
                        <a href="../Admin/create_new_admin.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-user-plus"></i>
                            <span>Add Admin</span>
                        </a>
                    </li>
                    <li>
                        <a href="../Admin/support_ticket.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-ticket-alt"></i>
                            <span>Support Ticket</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        <div class="container mx-auto px-4 py-8 max-w-4xl flex-1 overflow-y-auto">
            <header class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-indigo-700">Task Buddy</h1>
                        <p class="text-gray-500">Create professional surveys with ease</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition">
                            <i class="fas fa-user mr-2"></i>Admin
                        </button>
                    </div>
                </div>
            </header>

            <main>
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-plus-circle text-indigo-500 mr-3"></i> Create New Survey
                    </h2>
                    
                    <form method="POST" action="" id="surveyForm">
                        <div class="mb-8">
                            <div class="mb-6">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Survey Title*</label>
                                <input type="text" id="title" name="title" required
                                     class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                     placeholder="Enter survey title" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                            </div>
                            
                            <div class="mb-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="description" name="description" rows="3"
                                     class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                     placeholder="Describe the purpose of this survey"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                            </div>
                            
                            <div class="mb-6">
                                <label for="reward" class="block text-sm font-medium text-gray-700 mb-1">Reward Amount*</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">$</span>
                                    </div>
                                    <input type="number" id="reward" name="reward" step="0.01" required
                                         class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                         placeholder="0.00" value="<?php echo isset($_POST['reward']) ? htmlspecialchars($_POST['reward']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-8">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-medium text-gray-800 flex items-center">
                                    <i class="fas fa-question-circle text-indigo-500 mr-2"></i> Survey Questions
                                </h3>
                                <button type="button" onclick="addQuestion()"
                                     class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center">
                                    <i class="fas fa-plus mr-2"></i> Add Question
                                </button>
                            </div>
                            
                            <div id="questions" class="space-y-4">
                                </div>
                        </div>
                        
                        <div class="flex justify-end space-x-4">
                            <button type="button" onclick="resetForm()"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                                Reset
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center">
                                <i class="fas fa-save mr-2"></i> Create Survey
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>

    <div id="questionTemplate" class="hidden">
        <div class="question-card bg-white border border-gray-200 rounded-lg p-4 fade-in">
            <div class="flex justify-between items-start mb-3">
                <h4 class="font-medium text-gray-700">Question <span class="question-number">1</span></h4>
                <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Question Text*</label>
                <input type="text" name="questions[0][text]" required
                     class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                     placeholder="Enter your question">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Question Type*</label>
                    <select name="questions[0][type]"
                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="multiple choice">Multiple Choice</option>
                        <option value="text">Text Answer</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correct Answer</label>
                    <input type="text" name="questions[0][correct_answer]"
                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                         placeholder="Enter correct answer (optional)">
                </div>
            </div>
            
            <div class="options-container mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Options (for Multiple Choice)</label>
                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <input type="text" name="questions[0][options][0][option_text]" required
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                            placeholder="Option 1">
                        <input type="checkbox" name="questions[0][options][0][is_correct]" value="1"
                            class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <label class="text-sm font-medium text-gray-700">Correct</label>
                        <button type="button" onclick="addOption(this)" class="text-green-500 hover:text-green-700">
                            <i class="fas fa-plus-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
            

            <input type="hidden" name="questions[0][order_num]" value="1" class="order-input">
        </div>
    </div>

    <script>
        let questionCount = 0;
        
        // Add a new question
        function addQuestion() {
            const questionsDiv = document.getElementById('questions');
            const template = document.getElementById('questionTemplate');
            
            // Clone the template
            const newQuestion = template.cloneNode(true);
            newQuestion.classList.remove('hidden');
            
            // Update question number and inputs
            const questionNumber = questionCount + 1;
            newQuestion.querySelector('.question-number').textContent = questionNumber;
            
            // Update all input names and values
            const inputs = newQuestion.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                const name = input.getAttribute('name').replace('[0]', `[${questionCount}]`);
                input.setAttribute('name', name);
                
                if (input.classList.contains('order-input')) {
                    input.value = questionNumber;
                }
                input.value = '';
            });
             const optionsContainer = newQuestion.querySelector('.options-container');
            if (optionsContainer) {
                optionsContainer.innerHTML = `<label class="block text-sm font-medium text-gray-700 mb-2">Options (for Multiple Choice)</label>
                                            <div class="space-y-2">
                                                <div class="flex items-center space-x-2">
                                                    <input type="text" name="questions[${questionCount}][options][0][option_text]" required
                                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                                        placeholder="Option 1">
                                                    <input type="checkbox" name="questions[${questionCount}][options][0][is_correct]" value="1"
                                                        class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                                    <label class="text-sm font-medium text-gray-700">Correct</label>
                                                    <button type="button" onclick="addOption(this)" class="text-green-500 hover:text-green-700">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </button>
                                                </div>
                                            </div>`;
            }
            
            // Add to DOM
            questionsDiv.appendChild(newQuestion);
            questionCount++;
            
            // Scroll to the new question
            newQuestion.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        
        // Remove a question
        function removeQuestion(button) {
            const questionCard = button.closest('.question-card');
            questionCard.classList.add('hidden');
            questionCard.querySelectorAll('input, select, textarea').forEach(input => input.disabled = true);
            
            // Update question numbers for remaining questions
            updateQuestionNumbers();
        }
        
        // Update question numbers after removal
        function updateQuestionNumbers() {
            const questions = document.querySelectorAll('.question-card:not(.hidden)');
            questions.forEach((question, index) => {
                question.querySelector('.question-number').textContent = index + 1;
                question.querySelector('.order-input').value = index + 1;
                
                // Update the array index in the name attribute
                const inputs = question.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    input.setAttribute('name', newName);
                });
            });
            
            questionCount = questions.length;
        }
        
        // Reset the entire form
        function resetForm() {
            if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
                document.getElementById('surveyForm').reset();
                document.getElementById('questions').innerHTML = '';
                questionCount = 0;
                addQuestion();
            }
        }
        
        // Add first question on page load
        document.addEventListener('DOMContentLoaded', addQuestion);


        function addOption(buttonElement) {
            const optionsContainer = buttonElement.closest('.options-container');
            const newOptionDiv = document.createElement('div');
            newOptionDiv.className = 'flex items-center space-x-2';
             const questionIndex = optionsContainer.querySelector('input[name*="questions"]').name.match(/\[(\d+)\]/)[1];

            newOptionDiv.innerHTML = `
                <input type="text" name="questions[${questionIndex}][options][${optionsContainer.children.length}][option_text]" required
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-indigo-500 transition"
                    placeholder="Option ${optionsContainer.children.length + 1}">
                <input type="checkbox" name="questions[${questionIndex}][options][${optionsContainer.children.length}][is_correct]" value="0"
                    class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                <label class="text-sm font-medium text-gray-700">Correct</label>
                <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            optionsContainer.appendChild(newOptionDiv);
        }

        function removeOption(buttonElement) {
            const optionDiv = buttonElement.closest('.flex.items-center.space-x-2');
            optionDiv.remove();
        }
    </script>
</body>
</html>
