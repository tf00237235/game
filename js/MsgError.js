let strings = [{
        name: 'system',
        content: "",
    },
    {
        name: 'system',
        content: "[系統] 錯誤的操作方式",
    },

];

const type = new Type(strings, 30);
type.typingEffect();

function back_city(form) {
    console.log(form);
    form.type.value = 'start_game';
    form.method = 'post';
    form.action = "./home.php"
    form.submit();
}