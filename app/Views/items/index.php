<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Shopping List</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Shopping List</h1>
        <form id="add-form">
            <input type="text" id="item-name" placeholder="Add new item..." required>
            <button type="submit">Add</button>
        </form>

        <ul id="items-list">
            <!-- items will be injected here -->
        </ul>
    </div>

    <script src="/js/app.js"></script>
</body>

</html>