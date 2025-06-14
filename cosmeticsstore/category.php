<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .scrolling-div {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto; /* Enable horizontal scrolling */
            white-space: nowrap;
            gap: 20px;
            padding: 10px;
        }

        .category-item {
            display: inline-flex;
            align-items: center;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: #000;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .category-item:hover {
            transform: scale(1.05);
        }

        .category-item img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            margin-right: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .scrolling-div {
                overflow-x: auto; /* Enable horizontal scrolling for small screens */
                -webkit-overflow-scrolling: touch; /* Smooth scrolling on mobile */
            }
        }

        

        
    </style>
</head>
<body class=' bg-dark'>
    <div class="container mt-4">
        <div class="scrolling-div">
            <a class="category-item">
                <img src="./images/medicines.webp" alt="Category 1">
                <span>Medicines</span>
            </a>
            <a class="category-item">
                <img src="./images/mom-baby1.svg" alt="Category 2">
                <span>Mom & Baby</span>
            </a>
            <a class="category-item">
                <img src="./images/nutrition.jpg" alt="Category 3">
                <span>Nutrition</span>
            </a>
            <a class="category-item">
                <img src="./images/skin-care.jpeg" alt="Category 4">
                <span>Skin Care</span>
            </a>
            <a class="category-item">
                <img src="./images/personal-care.png" alt="Category 5">
                <span>Personal Care</span>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
