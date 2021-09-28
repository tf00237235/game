let isStop = false;

function scroll() {
    var elem = document.querySelector('.board');
    elem.scrollTop = elem.scrollHeight;
}

function dq(selector) {
    return document.querySelector(selector);
}


class Type {
    constructor(strings, speed = 50) {
        this.strings = strings
        this.countStr = 0;
        this.countChar = 0;
        this.timer = 0;
        this.speed = speed;
    }

    typingEffect() {
        if (this.countChar < this.strings[this.countStr].content.length) {
            dq('.board__content:last-child').innerHTML += this.strings[this.countStr].content.charAt(this.countChar);
            this.countChar++;
            this.timer = setTimeout(this.typingEffect.bind(this), this.speed);
        } else if (this.countStr < this.strings.length - 1) {
            this.countStr++;
            this.countChar = 0;
            const item = document.createElement('div');
            item.className = 'board__content';
            //this.switchStyle(item);
            dq('.board').append(item);

            this.typingEffect();
            scroll();
        }
        if (this.countChar == this.strings[this.countStr].content.length & this.countStr == this.strings.length - 1) {
            change_footer();
        }
    }
    typingEffect_no_change_footer() {
        if (this.countChar < this.strings[this.countStr].content.length) {
            dq('.board__content:last-child').innerHTML += this.strings[this.countStr].content.charAt(this.countChar);
            this.countChar++;
            this.timer = setTimeout(this.typingEffect_no_change_footer.bind(this), this.speed);
        } else if (this.countStr < this.strings.length - 1) {
            this.countStr++;
            this.countChar = 0;
            const item = document.createElement('div');
            item.className = 'board__content';
            //this.switchStyle(item);
            dq('.board').append(item);

            this.typingEffect_no_change_footer();
            scroll();
        }
    }

    switchStyle(item) {
        switch (this.strings[this.countStr].name) {
            case 'other':
                item.classList.add('name--text');
                break;
            case 'system':
                item.classList.add('system--text');
                break;
            default:
                break;
        }
    }
}