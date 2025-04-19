<?php
session_start();

// Database connection using PDO
$servername = "localhost"; // Change if necessary
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "task_buddy_db"; // Updated database name

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $survey_id = $_POST['survey_id'];
    $user_id = $_SESSION['user_id'] ?? null; // Use session user_id

    if (!$user_id) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    // Check if the user has already participated in the survey
    $checkStmt = $conn->prepare("SELECT * FROM user_surveys WHERE user_id = :user_id AND survey_id = :survey_id");
    $checkStmt->execute(['user_id' => $user_id, 'survey_id' => $survey_id]);
    $existingParticipation = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingParticipation) {
        echo json_encode(['success' => false, 'message' => 'You have already participated in this survey.']);
        exit; // Stop further execution
    }

    // Insert into user_surveys table
    $stmt = $conn->prepare("INSERT INTO user_surveys (user_id, survey_id, status) VALUES (:user_id, :survey_id, 'completed')");
    $stmt->execute(['user_id' => $user_id, 'survey_id' => $survey_id]);

    // Fetch correct answers
    $correctAnswers = [];
    foreach ($_POST['answers'] as $question_id => $answer) {
        $optionsQuery = "SELECT * FROM options WHERE question_id = :question_id AND is_correct = 1";
        $optionsStmt = $conn->prepare($optionsQuery);
        $optionsStmt->execute(['question_id' => $question_id]);
        $correctOption = $optionsStmt->fetch(PDO::FETCH_ASSOC);
        $correctAnswers[$question_id] = $correctOption['option_text'] ?? null; // Store correct answer
    }

    // Check answers and update user status
    $isDisqualified = false;
    foreach ($_POST['answers'] as $question_id => $answer) {
        // Normalize both strings for comparison
        $correctAnswerNormalized = strtolower(trim($correctAnswers[$question_id] ?? ''));
        $userAnswerNormalized = strtolower(trim($answer));
        if ($correctAnswerNormalized !== $userAnswerNormalized) {
            $isDisqualified = true; // User provided a wrong answer
            break;
        }
    }

    // Update user status based on answers
    if ($isDisqualified) {
        // Logic for disqualification
        $message = "You have been disqualified for providing incorrect answers.";
        echo json_encode(['success' => false, 'message' => $message]);
        exit;
    } else {
        // Fetch reward amount from surveys table
        $rewardStmt = $conn->prepare("SELECT reward FROM surveys WHERE id = :survey_id");
        $rewardStmt->execute(['survey_id' => $survey_id]);
        $rewardRow = $rewardStmt->fetch(PDO::FETCH_ASSOC);
        $rewardAmount = $rewardRow ? floatval($rewardRow['reward']) : 0;

        // Logic for rewarding the user
        $updateRewardStmt = $conn->prepare("UPDATE users SET reward = reward + :rewardAmount, completed_tasks = completed_tasks + 1, balance = balance + :rewardAmount WHERE id = :user_id");
        $updateRewardStmt->execute(['rewardAmount' => $rewardAmount, 'user_id' => $user_id]);
        
        $message = "Thank you for participating! You have been rewarded.";
        echo json_encode(['success' => true, 'message' => $message, 'reward' => $rewardAmount]);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}
?>
