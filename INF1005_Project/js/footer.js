/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/ClientSide/javascript.js to edit this template
 */

const form = document.querySelector('#newsletter-form');
const emailInput = document.querySelector('#email');
const submitBtn = document.querySelector('#submit-btn');
const message = document.querySelector('#message');

form.addEventListener('submit', (event) => {
  event.preventDefault(); // Prevent form submission

  // Display "submitted" message
  message.textContent = 'Thank Yor For Subscribing!';

  // Clear email input
  emailInput.value = '';

  // Remove "submitted" message after 3 seconds
  setTimeout(() => {
    message.textContent = '';
  }, 2000);
});
