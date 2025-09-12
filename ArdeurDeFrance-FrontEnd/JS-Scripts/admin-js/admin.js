$(document).ready(function() {
    // Store the original title and description placeholders
    var originalTitle = "Ardeur De France";
    var originalDescription = "Luxury that owns quality";

    // Hide all tables initially
    $(".table-container > div").hide();
    
    // Add click event listener to each dashboard item
    $(".dashboard").click(function() {
        // Get the data-target attribute value
        var target = $(this).attr("data-target");

        // Hide all tables
        $(".table-container > div").hide();

        if (target === "best-products") {
            // Set the title and description to the original placeholders
            $(".table-title-container h2").text(originalTitle).show();
            $(".table-title-container p").text(originalDescription).show();
        } else {    
            // Show the corresponding table based on the data-target value
            $("#" + target).show();

            // Scroll to the corresponding table
            $('html, body').animate({
                scrollTop: 0
            }, 1000);
            
            var title = $(this).find("h1").text();
            var description = $(this).find("p").text();
            
            $(".table-title-container h2").text(title).show();
            $(".table-title-container p").text(description).show();
        }
    });

    // Calculate total number of orders
    var totalOrders = $("#total-orders-table tbody tr").length;
    
    // Display total number of orders
    $("#total-orders-count").text(totalOrders);
    





    // Print functionality
    $("#print-sales-summary-button").click(function() {
        printSpecificTable("sales-summary-table");
    });

    $("#print-users-table").click(function() {
        printSpecificTable("total-users-table");
    })

    $("#recent-orders-table").click(function(){
        printSpecificTable("recent-orders-table");
    })



    // Show add modal when "Add New Item" button is clicked
    $("#add-new-item-button").click(function() {
        $("#add-modal").show();
    });

    // Close the modal when the close button is clicked
    $(".close").click(function() {
        $("#add-modal").hide();
    });

    // Close the modal when the user clicks outside of it
    $(window).click(function(event) {
        if (event.target == $("#add-modal")[0]) {
            $("#add-modal").hide();
        }
    });

    // Function to perform search
    function searchTable(inputId, tableId) {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById(inputId);
        filter = input.value.toUpperCase();
        table = document.getElementById(tableId);
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            for (var j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        break;
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    }

    // Add event listener to search input fields
    $('#search-users').on('keyup', function () {
        searchTable('search-users', 'total-users-table');
    });

    $('#search-total-orders').on('keyup', function () {
        searchTable('search-total-orders', 'total-orders-table');
    });

    $('#search-sales-summary').on('keyup', function () {
        searchTable('search-sales-summary', 'sales-summary-table');
    });

    $('#search-manage-inventory').on('keyup', function () {
        searchTable('search-manage-inventory', 'manage-inventory');
    });

    $('#search-recent-orders').on('keyup', function () {
        searchTable('search-recent-orders', 'recent-orders-table');
    });

    $('#search-manage-orders').on('keyup', function () {
        searchTable('search-manage-orders', 'manage-orders-table');
    });

    function printSpecificTable(tableId) {
        // Get the table contents and the table's name (assumed to be in a <caption> tag or set manually)
        var divContents = document.getElementById(tableId).innerHTML;
        var tableElement = document.getElementById(tableId);
    
        // You can assume the table's name is in a <caption> tag within the table, or set it manually
        var tableName = tableElement.getAttribute("data-table-name") || "Table Print Preview"; // Default if no name is provided
    
        // Open a new window for printing
        var printWindow = window.open('', '', 'height=600,width=800');
    
        // Write the HTML structure for the print window
        printWindow.document.write('<html><head><title>Print Table</title>');
    
        // Add formal styles for the table and document
        printWindow.document.write(`
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    color: #333;
                    line-height: 1.6;
                }
                h1 {
                    text-align: center;
                    font-size: 24px;
                    margin-bottom: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                    font-size: 14px;
                }
                th, td {
                    border: 1px solid black;
                    padding: 12px;
                    text-align: center;
                    background-color: #f9f9f9;
                }
                th {
                    background-color: #f1f1f1;
                    font-weight: bold;
                }
                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }
            </style>
        `);
    
        // Close the head and start the body, including the dynamic table name as header
        printWindow.document.write('</head><body>');
    
        // Use the table's name as the printout header
        printWindow.document.write(`<h1>${tableName}</h1>`);
    
        // Add the actual table content
        printWindow.document.write(divContents);
    
        // Close the body and html tags
        printWindow.document.write('</body></html>');
    
        // Close the document to finish writing
        printWindow.document.close();
    
        // Trigger the print dialog
        printWindow.print();
    }
    
    
});
