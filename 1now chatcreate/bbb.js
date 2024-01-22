$(document).ready(function() {
    console.log("Document is ready. Datepicker initialization.");
    // Datepickerを適用
    $(".datepicker").datepicker();
});

function aaa(){
    console.log("Function aaa() called.");
    fetch("b.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.text();
        })
        .then(data => {
            console.log("Data received:", data);
            document.getElementById("content").innerHTML = data;
        })
        .catch(error => {
            console.error("There has been a problem with your fetch operation:", error);
        });   
}
