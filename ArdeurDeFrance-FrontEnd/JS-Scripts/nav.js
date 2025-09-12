$(document).ready(function(){
    // Toggle navbar when menu button is clicked
    $("#MainFormHeaderMenuButton").click(function(){
      $("#navbar").toggle();
      // Prevent the click event from propagating to the document body
      return false;
    });
    
    // Hide navbar when clicked outside of it
    $(document).click(function(event) { 
      // Check if the clicked element is not the navbar or the menu button
      if (!$(event.target).closest('#navbar').length && !$(event.target).is('#MainFormHeaderMenuButton')) {
        // Hide the navbar
        $("#navbar").hide();
      }        
    });
  });