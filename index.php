    <?php
    include './admin/confi.php';
    session_start();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $cartCount = count($_SESSION['cart']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $dish_id = $_POST['dish_id'];
        $item_exists = false;
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['dish_id'] == $dish_id) {
                $_SESSION['cart'][$key]['quantity']++;
                $item_exists = true;
                break;
            }
        }

        if (!$item_exists) {
            $dish_name = $_POST['dish_name'];
            $dish_price = $_POST['dish_price'];

            $_SESSION['cart'][] = array(
                'dish_id' => $dish_id,
                'dish_name' => $dish_name,
                'dish_price' => $dish_price,
                'quantity' => 1,
            );
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit();
    }

    // Fetch the current open and close times and status
    $status_query = "SELECT open_time, close_time, status FROM restaurant_time WHERE id = 1";
    $status_result = $conn->query($status_query);
    $status_row = $status_result->fetch_assoc();
    $open_time = $status_row['open_time'];
    $close_time = $status_row['close_time'];
    $current_status = $status_row['status'];
    ?>

    <!doctype html>
    <html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- CSS -->
        <link rel="stylesheet" href="fronted.css">

        <title>Food Ordering</title>
    <style>
    *{
        text-transform: capitalize;
    }
        .scrollable-menu {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            padding-top: 10px;
            scrollbar-width: none;
        }

        .scrollable-menu::-webkit-scrollbar {
            display: none;
        }

        .menu-item li {
            padding: 2px 20px;
            border-radius: 25px;
            border: 1px solid black;
            margin: 0 5px;

            text-align: center;
            list-style: none;

        }
        li a{
            text-decoration: none;
            color: red;
        }
        .menu-item li:hover{
            color: blue;
            border-color: blue;
        }
        .menu-item li a.selected {
            color: blue;
            border-color: blue;
        }

    </style>
    </head>
    <body>
        <div class="fixed-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col">
                        <nav class="navbar ">
                            <a class="navbar-brand">Hotel Name</a>
                            <!-- Cart count -->
                        </nav>
                    </div>                   
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-6 pt-3 text-info">
                        <?php
                            echo htmlspecialchars($open_time) . " TO " . htmlspecialchars($close_time);
                           
                            ?>
                    </div>
                    <div class="col-6">
                        <?php
                            // Display restaurant status
                            if ($current_status == 'close') {
                                echo "<div class='alert-c' role='alert'>Restaurant Closed</div>";
                            } else {
                                echo "<div class='alert-o' role='alert'>Restaurant Open</div>";
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="container mt-2">
                <div class="row">
                    <div class="col-md-12">
                        <form class="d-flex" role="search" method="GET" action="index.php">
                            <input class="form-control me-2" type="search" name="search_query" placeholder="Search Food Items" aria-label="Search">
                            <button class="btn btn-outline-success btn-sm" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
            <!-- coupon code 
        <div class="container">
            <div class="row">
                <div class="col">
                    
                </div>
            </div>
        </div> -->
        <div class="container">
    <div class="row">
        <div class="col-md-12 ">
            <?php
            // Ensure $conn is not null before querying
            if ($conn) {
                $cat_res = mysqli_query($conn, "SELECT * FROM category WHERE status = 1 ORDER BY id ASC");
                if (!$cat_res) {
                    echo "Error: " . mysqli_error($conn);
                } else {
                    ?>
                    <div class="category-title pt-3 text-center" style="margin-top:100px">
                        <br><h4>Categories</h4>
                    </div>
                    <div class="menu-item scrollable-menu">
                        <li><a href='index.php?cat_id=0' <?php if (!isset($_GET['cat_id']) || $_GET['cat_id'] == 0) echo "class='selected'"; ?>>All</a></li> <!-- Display 'All' category -->
                        <?php
                        while ($cat_row = mysqli_fetch_assoc($cat_res)) {
                            $cat_id = $cat_row['id'];
                            $selected_class = (isset($_GET['cat_id']) && $_GET['cat_id'] == $cat_id) ? "class='selected'" : "";
                            echo "<li><a href='index.php?cat_id=$cat_id' $selected_class>" . $cat_row['category'] . "</a></li>";
                        }
                        ?>
                    </div>

                    <?php
                }
            } else {
                echo "Error: Database connection not established.";
            }
            ?>
        </div>
    </div>
</div>

        <div class="container pt-4">
            <div class="row">
                <div class="col">
                    <?php
    // Ensure $conn is not null before querying
    if ($conn) {
        $product_sql = "SELECT * FROM dish WHERE status = 1";
        if (isset($_GET['cat_id']) && $_GET['cat_id'] > 0) {
            $cat_id = $_GET['cat_id'];
            $product_sql .= " AND category_id = '$cat_id' ";
        }

        if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
            $search_query = mysqli_real_escape_string($conn, $_GET['search_query']);
            $product_sql .= " AND (dish LIKE '%$search_query%' OR dish_detail LIKE '%$search_query%')";
        }
        $product_sql .= " ORDER BY dish DESC";

        $product_result = mysqli_query($conn, $product_sql);

        if (!$product_result) {
            echo "Error: " . mysqli_error($conn);
        } else {
            while ($product_row = mysqli_fetch_assoc($product_result)) {
                $cardClass = ($current_status == 'close') ? 'grayscale' : '';
                ?>
                                <div class="card mb-3" >
                                    <div class="row g-0">
                                        <div class="col-8">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo $product_row['dish'] ?></h5>
                                                <p class="card-text"><?php echo $product_row['dish_detail'] ?></p>
                                                <h5><b><?php echo $product_row['dish_price'] ?></b></h5>
                                            </div>
                                        </div>
                                        <div class="col-4 p-5 d-flex align-items-center">
        <?php if ($current_status == 'close'): ?>
            <div class="addToCartForm w-100" style="color: black;">
                <input type="hidden" name="dish_id" value="<?php echo $product_row['id']; ?>">
                <input type="hidden" name="dish_name" value="<?php echo $product_row['dish']; ?>">
                <input type="hidden" name="dish_price" value="<?php echo $product_row['dish_price']; ?>">
                <button type="button" class="btn btn-primary addToCartBtn disabled">Add+</button>
            </div>
        <?php else: ?>
            <form class="addToCartForm w-100" method="post">
                <input type="hidden" name="dish_id" value="<?php echo $product_row['id']; ?>">
                <input type="hidden" name="dish_name" value="<?php echo $product_row['dish']; ?>">
                <input type="hidden" name="dish_price" value="<?php echo $product_row['dish_price']; ?>">
                <button type="submit" class="btn btn-primary addToCartBtn">Add+</button>
            </form>
        <?php endif;?>
    </div>

                                    </div>
                                </div>
                                <?php
    }
        }
    } else {
        echo "Error: Database connection not established.";
    }
    ?>
                </div>
            </div>
        </div>

    <!-- Attractive message popup -->
    <div id="cartMessagePopup">
        <span class="cart-count"><?php echo $cartCount; ?></span>
        <span id="cartMessage"></span>
        <button class="btn btn-primary" onclick="window.location.href='cart.php'"><i class="fas fa-arrow-right fas fa-shopping-cart"></i> View</button>
    </div>

    <!-- Cart item container -->
    <div class="fixed-cart-container">
        <!-- Cart item content here -->
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Function to update cart count
            function updateCartCount() {
                const cartCountElement = document.querySelector('.cart-count');
                const currentCount = <?php echo $cartCount; ?>;
                cartCountElement.textContent = currentCount;
            }

            // Show message popup function
            function showMessagePopup() {
                const cartMessagePopup = document.getElementById('cartMessagePopup');
                const cartMessage = document.getElementById('cartMessage');
                cartMessage.textContent = 'Item';
                cartMessagePopup.classList.add('show');

                // Hide message popup after 3 seconds
                // setTimeout(() => {
                //     cartMessagePopup.classList.remove('show');
                // }, 3000);
            }

            // Update cart count and show cart message popup on page load
            updateCartCount();
            showMessagePopup();

            // Select all forms with the class 'addToCartForm'
            const addToCartForms = document.querySelectorAll('.addToCartForm');

            // Loop through each form
            addToCartForms.forEach(function(form) {
                // Add an event listener for form submission
                form.addEventListener('submit', function(event) {
                    event.preventDefault(); // Prevent default form submission

                    // Increment the cart count
                    const cartCountElement = document.querySelector('.cart-count');
                    const currentCount = parseInt(cartCountElement.textContent);
                    cartCountElement.textContent = currentCount + 1;

                    // Show message popup
                    showMessagePopup();

                    // Submit the form via AJAX (optional)
                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Handle response if needed
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        });
    </script>
    </body>
    </html>