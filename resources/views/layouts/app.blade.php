<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <style>
    /* Table Styling */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family:Verdana, Geneva, Tahoma, sans-serif;
        user-select: none;
    }
    body, html {
      font-size: 0.9em;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #007BFF;
        color: white;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }

    h2 {
        font-size: 1em;
        margin-bottom: 20px;
    }

    input, textarea, select {
        font-size: 1em;
        border-radius: 5px;
        padding: 8px 15px;
        width: 100%;
    }

    /* Button Styling */
    .edit-btn, .delete-btn, .add-btn, .submit-btn, .cancel-btn {
        padding: 8px 12px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        border-radius: 5px;
    }

    .edit-btn {
        background-color: #ffc107;
        color: black;
    }

    .delete-btn {
        background-color: #dc3545;
        color: white;
    }

    .add-btn {
        background-color:rgb(9, 46, 84);
        color: white;
    }

    .submit-btn {
        background-color:rgb(6, 88, 62);
        color: white;
    }

    .cancel-btn {
        background-color:rgb(165, 59, 13);
        color: white;
    }

    .edit-btn:hover {
        background-color: #e0a800;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }

    .add-btn:hover {
        background-color:rgb(8, 38, 69);
    }

    .submit-btn:hover {
        background-color:rgb(5, 66, 47);
    }

    .cancel-btn:hover {
        background-color:rgb(135, 49, 13);
    }

    .title-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
    }


    /* Responsive Design */
    @media screen and (max-width: 768px) {
        table {
            font-size: 12px;
        }
        
        th, td {
            padding: 8px;
        }

        .edit-btn, .delete-btn {
            font-size: 12px;
            padding: 6px 10px;
        }
    }
  </style>

</head>
<body style="padding: 50px">
  <nav>
    <ul>
      <li><a href="{{ route('books.index') }}">Books</a></li>
      <li><a href="{{ route('authors.index') }}">Authors</a></li>
      <li><a href="{{ route('categories.index') }}">Categories</a></li>

      <div class="container">
        @yield('content')
    </div>
    </ul>
  </nav>
</body>
</html>