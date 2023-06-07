
let TextToSpeechMixin = {
    data: function () {
        return {
            playingNow: false,
            paused: false,
        }
    },
    computed: {
        isTextPlaying() {
            return this.playingNow;
        },
        isTextPaused() {
            return this.paused;
        },
    },
    created: function () {
    },
    methods: {
        playText(text) {
            var msg = new SpeechSynthesisUtterance();
            var voices = speechSynthesis.getVoices();
            //console.log(voices)
            //msg.voice = voices[27];
            msg.text = text;
            speechSynthesis.speak(msg);
            this.playingNow = true;
        },

        pauseText() {
            speechSynthesis.cancel();
            this.playingNow = false;
        },
    }
};

export {TextToSpeechMixin};
