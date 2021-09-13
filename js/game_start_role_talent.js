$.ajax({
    type: "post",
    url: "./home.php",
    data: {
        type: "start_role_talent",
        role_id: document.getElementById("role_id").value,
        difficulty: document.getElementById("difficulty").value,
    },
    datatype: "json",
    success: function(result) {
        console.log(result);
        result = JSON.parse(result);
        const talent = new Type(result, 30);
        talent.typingEffect();
    },
});