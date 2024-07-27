<!DOCTYPE html>
<html lang="en">

<?php
session_start(); 
require 'vendor/autoload.php';

// Connect to MongoDB
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$careerDb = $mongoClient->careerDb;
$userCollection = $careerDb->user;

// Fetch user data based on $_SESSION['user']
$userData = $userCollection->findOne(['_id' => $_SESSION['user']]);

// Check if user data is found
if ($userData) {
    // Extract user information from $userData
    $fullName = $userData['full_name'];
    $email = $userData['email'];
    $age = $userData['age'];
    $gender = $userData['gender'];
    $place = $userData['place'];
    $educationLevel = $userData['education_level'];

    ?>
    <?php if (!isset($_POST['career'])) {
        header('Location:career_quiz.php');
    } ?>

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Career-Vertex User Career</title>
        <meta content="" name="description">
        <meta content="" name="keywords">
        

        <link href="assets/img/favicon.png" rel="icon">
        <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Roboto:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Work+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
            rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">


        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
        <link href="assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
        <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

        <link href="assets/css/main.css" rel="stylesheet">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('assets/img/recommend.jpg'); /* Specify the path to your background image */
            background-size: cover; /* Cover the entire viewport */
            background-position: center; /* Center the background image */
            color: #fff; /* Text color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card-title{
            color: #fff;
            font-size: 40px; /* Increased font size */
             font-weight: bold;
            animation: fadeIn 1s ease forwards
        }
        .card-text {
            color: #fff;
            font-size: 30px; /* Increased font size */
             font-weight: bold;
            animation: fadeIn 1s ease forwards/* Increased font size for better readability */
        }

        .career-button {
    background-color: #007bff;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    opacity: 0; /* Initially hidden */
    animation: fadeIn 1s ease forwards; /* Fade-in animation */
    font-size: 50px; /* Increased font size */
    font-weight: bold; /* Make careers bold */
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

        .content-container {
            padding: 30px; 
            border-radius: 20px;
            animation-delay: 2s; 
        }
    </style>
</head>
<body>
    <header class="bg-dark text-white p-4 fixed-top">
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <a href=".">
                        <h3>Career Vertex</h3>
                    </a>
                </div>
                <div class="col-md-2 text-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle  ml-1"></i>&nbsp;<?= ucwords($fullName) ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="user.php">Profile</a></li>
                            <li><a class="dropdown-item" href="index.php">Home</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Container for content below header -->
    <div class="content-container">
        <main id="main">
            <div class="container my-5">
                <h1 class="card-title">Careers in <span id="subject"></span></h1>
                <p class="card-text h5">Based on the analysis, here are three potential career paths you can explore:</p> 
                <div class="d-flex flex-column align-items-center" id="career-buttons"></div>
                <p class="card-text h5">Choose one and we can continue</p> 
            </div>
        </main><!-- End #main -->
    </div>

        

        <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>

        <div id="preloader"></div>

        <!-- Vendor JS Files -->
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/vendor/aos/aos.js"></script>
        <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
        <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
        <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
        <script src="assets/vendor/php-email-form/validate.js"></script>
        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <script>
            career = {
                "Physics": ["Physicist", "Optical Engineer", "Professor"],
                "Chemistry": ["Chemist", "Pharmaceutical Scientist", "Agrochemical Specialist"],
                "Biology": ["Biologist", "Medical Researcher", "Biotechnology Scientist"],
                "Mathematics": ["Mathematician", "Data Analyst", "Actuarial Analyst"],
                "Environmental_Science": ["Environmental Engineer", "Sustainability Consultant", "Forest Officer"],
                "Astronomy": ["Astronomer", "Satellite Engineer", "Planetarium Educator"],
                "Medicine": ["Physician", "Pharmacist", "Medical Devices Sales"],
                "Pharmacology": ["Pharmacologist", "Clinical Research Associate", "Medical Representative"],
                "Biochemistry": ["Biochemist", "Quality Control Analyst", "Food Technologist"],

                "Mechanical Engineering": ["Mechanical Engineer", "HVAC Specialist", "Automotive Engineer"],
                "Electrical Engineering": ["Electrical Engineer", "Power Systems Engineer", "Telecom Engineer"],
                "Civil Engineering": ["Civil Engineer", "Construction Manager", "Urban Planner"],
                "Chemical Engineering": ["Chemical Engineer", "Petroleum Refinery Engineer", "Plastic Processing Engineer"],
                "Computer Engineering": ["Software Developer", "Network Administrator", "IT Support Technician"],
                "Aerospace Engineering": ["Aerospace Engineer", "Aircraft Maintenance Engineer", "Satellite Ground Segment Engineer"],
                "Biomedical Engineering": ["Biomedical Engineer", "Medical Equipment Sales", "Prosthetics Technician"],
                "Environmental Engineering": ["Environmental Engineer", "Waste Management Consultant", "Renewable Energy Engineer"],
                "Industrial Engineering": ["Industrial Engineer", "Production Manager", "Logistics Manager"],
                "Software Engineering": ["Software Engineer", "Mobile App Developer", "Cybersecurity Analyst"],

                "Accounting": ["Chartered Accountant", "Tax Consultant", "Audit Associate"],
                "Finance": ["Financial Advisor", "Investment Banker", "Mutual Fund Distributor"],
                "Economics": ["Economist", "Policy Analyst", "Market Research Analyst"],
                "Business Management": ["General Manager", "Entrepreneur", "Operations Manager"],
                "Marketing": ["Marketing Manager", "Brand Manager", "Digital Marketing Executive"],
                "Human Resource Management": ["HR Manager", "Talent Acquisition Specialist", "Training and Development Coordinator"],
                "Supply Chain Management": ["Supply Chain Manager", "Logistics Coordinator", "Procurement Analyst"],
                "International Business": ["International Business Consultant", "Export-Import Manager", "Global Marketing Specialist"],
                "Entrepreneurship": ["Startup Founder", "Small Business Owner", "Social Entrepreneur"],
                "Corporate Law": ["Corporate Lawyer", "Compliance Officer", "Contract Negotiator"],
                "Organizational Behavior": ["Organizational Development Consultant", "Change Management Specialist", "HR Business Partner"],
                "Risk Management": ["Risk Analyst", "Insurance Underwriter", "Corporate Risk Manager"],
                "Business Analytics": ["Business Analyst", "Data Scientist", "Business Intelligence Consultant"],
                "Financial Planning": ["Financial Planner", "Wealth Manager", "Investment Advisor"],

                "History": ["Historian", "Museum Curator", "Archivist"],
                "Literature": ["Author", "Editor", "Copywriter"],
                "Philosophy": ["Philosophy Professor", "Ethics Consultant", "Policy Analyst"],
                "Art_History": ["Art Historian", "Museum Curator", "Art Conservator"],
                "Cultural_Studies": ["Cultural Anthropologist", "Diversity and Inclusion Specialist", "Intercultural Trainer"],
                "Religious_Studies": ["Religious Scholar", "Chaplain", "Religious Educator"],
                "Linguistics": ["Linguist", "Language Translator", "Speech Therapist"],
                "Archaeology": ["Archaeologist", "Heritage Conservationist", "Museum Educator"],
                "Anthropology": ["Anthropologist", "Social Researcher", "Cultural Advisor"],
                "Performing_Arts": ["Performing Artist", "Drama Therapist", "Arts Administrator"]
            }
            const predictedProfession = "<?= $_POST['career'] ?>"; // Replace with the actual predicted profession
            const subjectElement = document.getElementById('subject');
            const careerButtonsElement = document.getElementById('career-buttons');

            // Set the subject in the card title
            subjectElement.textContent = predictedProfession;

            // Generate the career buttons
            const careers = career[predictedProfession];
            careers.forEach(career => {
                const button = document.createElement('button');
                button.classList.add('btn', 'btn-lg', 'm-1', 'career-button', 'animate__animated', 'animate__fadeInUp');
                button.textContent = career;
                button.type = 'button';
                button.addEventListener('click', () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'save_career.php';

                    const careerInput = document.createElement('input');
                    careerInput.type = 'hidden';
                    careerInput.name = 'career';
                    careerInput.value = career;

                    const userInput = document.createElement('input');
                    userInput.type = 'hidden';
                    userInput.name = 'user_id';
                    userInput.value = '<?= $_SESSION["user"] ?>';

                    form.appendChild(careerInput);
                    form.appendChild(userInput);

                    document.body.appendChild(form);
                    form.submit();
                });
                careerButtonsElement.appendChild(button);
            });


        </script>
    </body>


</html>

<?php
} else {
    // Handle case where user data is not found
    header("Location: index.php");
}
?>
