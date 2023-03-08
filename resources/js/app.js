require('./bootstrap');
require('./parts/ajax_setup');
require('./parts/captcha');


let speech = new SpeechSynthesisUtterance();
speech.lang = "en";
var talkElements = document.getElementsByClassName("talk");
Array.from(talkElements).forEach(function(element) {
  element.addEventListener('click', (item) => {
    word = element.getElementsByClassName('word');
    let textToSpeech = word[0].textContent;
    speech.text = textToSpeech;
    console.log(textToSpeech);


    window.speechSynthesis.speak(speech);
  });
});
