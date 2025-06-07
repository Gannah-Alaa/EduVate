// document.addEventListener('DOMContentLoaded', function () {
//     const button = document.getElementById('showInputBtn');
//     const container = document.getElementById('inputContainer');
  
//     button.addEventListener('click', function () {
//       // Check if input already exists
//       if (!document.getElementById('dynamicInput')) {
//         const input = document.createElement('input');
//         input.type = 'text';
//         input.id = 'dynamicInput';
//         input.placeholder = 'Type something...';
//         container.appendChild(input);
//       }
//     });
//   });


document.addEventListener('DOMContentLoaded', function() {
    // Get references to the button and input container
    const button = document.getElementById('showInputButton');
    const inputContainer = document.getElementById('inputContainer');

    // Add a click event listener to the button
    button.addEventListener('click', function() {
        // Toggle the display of the input container
        if (inputContainer.style.display === 'none') {
            inputContainer.style.display = 'flex'; // Show the input
        } else {
            inputContainer.style.display = 'none'; // Hide the input
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('uploadBtn');
    const fileInput = document.getElementById('fileInput');
  
    button.addEventListener('click', function () {
      fileInput.click(); // Trigger the hidden file input
    });
  
    // fileInput.addEventListener('change', function () {
    //   if (fileInput.files.length > 0) {
    //     alert(`Selected file: ${fileInput.files[0].name}`);
    //   }
    // });
  });