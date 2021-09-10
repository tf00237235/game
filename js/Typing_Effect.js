class Type {
    constructor(text_name, text_content) {
        this.strings = text_name + "：" + text_content;
        this.i = 0;
        this.num = this.strings.length;
        this.timer = 0;
        this.speed = 50;
    }

    typeWriter() {
        if (this.i < this.strings.length) {
            document.getElementById("main").innerHTML += this.strings.charAt(this.i);
            this.i++;
            setTimeout(this.typeWriter, this.speed);
        }
    }
}