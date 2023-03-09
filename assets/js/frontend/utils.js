// Add and export you global functions that you want to use in multiple files.
// You can use them easily by importing this file in other files.
// Hint: Check the checkout-page.js file for example.
export const getQueryVariable = (variable) => {
    const query = window.location.search.substring(1);
    const vars = query.split("&");

    for (let i = 0; i < vars.length; i++) {
        let pair = vars[i].split("=");

        if (pair[0] === variable) {
            return pair[1];
        }
    }

    return false;
}