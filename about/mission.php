<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Sparkle Wash</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('https://demo.htmlcodex.com/2161/car-repair-html-template/img/carousel-bg-2.jpg') no-repeat center center/cover;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.5); /* semi-transparent overlay */
        }

        .modal {
            background-color: rgba(255, 255, 255, 0.8); /* Added transparency to the modal background */
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            max-width: 800px;
            width: 100%;
            max-height: 80%;
            overflow-y: auto;
            position: relative;
            text-align: center;
        }

        .return-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1em;
            background-color: rgb(22, 192, 204);
            color: #fff;
            border: 2px solid #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.5s, color 0.5s;
        }

        .return-button:hover {
            background-color: blue;
            color: #fff;
        }

        .modal h1 {
            margin: 0;
            color: #1900ff;
            font-size: 40px;
            font-weight: 800;
            letter-spacing: 1px;
            text-align: center;
            position: inherit;
        }

        .modal h1 span {
            color: red;
        }

        .modal p {
            margin-top: 20px;
            color: #333;
            line-height: 1.6;
        }

        .modal h2 {
            margin-top: 30px;
            font-size: 30px;
            color: black;
            text-align: center;
        }

        .modal ul {
            margin-top: 10px;
            padding-left: 20px;
            text-align: left;
            list-style: none;
        }

        .modal ul li {
            margin-bottom: 10px;
            color: #333;
        }

        .modal ul li strong {
            color: #1900ff;
        }
    </style>
</head>
<body>

<!-- Floating Card Modal -->
<div class="modal-overlay">
    <div class="modal">
        <h1>Sparkle<span>Wash</span></h1>
        <h2>Our Mission</h2>
        <p>
            Our mission is simple: to provide our customers with an exceptional car 
            washing experience that exceeds their expectations. 
            We strive to deliver unparalleled cleanliness and shine to every vehicle that passes through our doors.
        </p>
        <h2>Our Values</h2>
        <ul>
            <li><strong>Quality:</strong> At SparkleWash, we are committed to delivering top-quality car wash services, 
            ensuring that every vehicle leaves our facility sparkling clean and rejuvenated.</li>
            <li><strong>Customer Satisfaction:</strong> Our customers' satisfaction is our priority at SparkleWash. 
            We strive to provide an exceptional car wash experience that leaves them delighted and coming back for more.</li>
            <li><strong>Environmental Responsibility:</strong> SparkleWash is dedicated to environmental responsibility. 
            We employ eco-friendly practices and use biodegradable products to minimize our impact on the environment while delivering outstanding car wash results.</li>
            <li><strong>Excellence:</strong> At SparkleWash, we are committed to excellence in everything we do, striving to exceed
            industry standards and deliver exceptional service to our customers.</li>
            <li><strong>Integrity:</strong> At SparkleWash, we conduct our business with honesty, transparency, and integrity,
            building trust with our customers, employees, and communities.</li>
            <li><strong>Innovation:</strong> At SparkleWash, we embrace innovation and continuous improvement, leveraging the latest technologies
            and practices to enhance the car wash experience and minimize our environmental impact.</li>
            <li><strong>Customer Focus:</strong> At SparkleWash, our customers are at the heart of everything we do. 
            We listen to their feedback, anticipate their needs, and always strive to provide personalized, attentive service.</li>
            <li><strong>Teamwork:</strong> At SparkleWash, we foster a culture of collaboration and respect, 
            recognizing that our success depends on the collective efforts of our team members.</li>
        </ul>
        <h2>Vision</h2>
        <p>
            Our vision is to become the leading provider of car wash services, known for our unwavering commitment to quality, 
            innovation, and customer satisfaction. We aim to expand our presence across regions, offering convenient, eco-friendly 
            car wash solutions that exceed the expectations of our customers. By continuously investing in our people, processes, and technology, 
            we aspire to set new standards for excellence in the car wash industry while making a positive impact on the environment and the communities we serve. 
            Together, we envision a future where every car owner can enjoy a sparkling clean vehicle and an unparalleled car wash experience at SparkleWash.
        </p>
        <button class="return-button" onclick="window.location.href = 'about.php';">Back</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modalOverlay = document.querySelector(".modal-overlay");
        const closeModalBtn = document.querySelector(".close-modal");
        const returnButton = document.querySelector(".return-button");

        function closeModal() {
            modalOverlay.style.display = "none";
        }
    });
</script>
</body>
</html>
