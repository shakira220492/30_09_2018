var position_lyrics = 0;

var d_estrofas_EditLyrics = 0;
var e_palabras_EditLyrics = 0;

estrofaAmount_EditLyrics = 40;
palabraAmount_EditLyrics = 40;

var lyricsWord_EditLyrics = new Array(estrofaAmount_EditLyrics);
for (var i=0; i<lyricsWord_EditLyrics.length; i++)
{
    lyricsWord_EditLyrics[i] = new Array(palabraAmount_EditLyrics);
    
    for (var j=0; j<lyricsWord_EditLyrics[i].length; j++)
    {
        lyricsWord_EditLyrics[i][j] = new Array(12);
    }
}

var speed = 0;
var speed_millisecond = 0;


//////////////////////////////////////////////////////////////////////////////////////////
var mouse_status = 0; // "0" si NO se puede dibujar, "1" si SI se puede dibujar

var firstTime = 0;
var middleTime = 0;
var lastTime = 0;

function draw_word()
{
    var respectlyValue = 0;
    
    // esta funcion me pinta los frames que no quedaron pintados
    draw_word_frames();
    
    // esta funcion me dibuja los bordes
    draw_word_border();
    
    // esta funcion me dibuja el input y las demás opciones
    draw_word_content();
}

function draw_word_frames()
{
    if (firstTime > lastTime)
    {
        for (i=lastTime; i<=firstTime; i++)
        {
            respectlyValue = i;
            var frameId = "frame"+respectlyValue;
            var thisWordId = "word_lyrics"+respectlyValue;
            var thisTimeId = "start_time"+respectlyValue;
            var frame = document.getElementById(frameId);
            var word_lyrics = document.getElementById(thisWordId);
            var start_time = document.getElementById(thisTimeId);

            frame.setAttribute("draw_status", "unavailable");
            frame.style.opacity = "1";
            word_lyrics.style.backgroundColor = "#1ab7ea";
            word_lyrics.style.cursor = "not-allowed";
            start_time.style.backgroundColor = "#1ab7ea";
            start_time.style.cursor = "not-allowed";
        }
    } else if (firstTime <= lastTime)
    {
        for (i=firstTime; i<=lastTime; i++)
        {
            respectlyValue = i;
            var frameId = "frame"+respectlyValue;
            var thisWordId = "word_lyrics"+respectlyValue;
            var thisTimeId = "start_time"+respectlyValue;
            var frame = document.getElementById(frameId);
            var word_lyrics = document.getElementById(thisWordId);
            var start_time = document.getElementById(thisTimeId);

            frame.setAttribute("draw_status", "unavailable");
            frame.style.opacity = "1";
            word_lyrics.style.backgroundColor = "#1ab7ea";
            word_lyrics.style.cursor = "not-allowed";
            start_time.style.backgroundColor = "#1ab7ea";
            start_time.style.cursor = "not-allowed";
        }
    }
    
}

function draw_word_border()
{   
    if (firstTime > lastTime)
    {
        var firstWord_lyrics = "word_lyrics"+lastTime;
        var lastWord_lyrics = "word_lyrics"+firstTime;

        var firstStart_time = "start_time"+lastTime;
        var lastStart_time = "start_time"+firstTime;
    } else if (firstTime <= lastTime)
    {
        var firstWord_lyrics = "word_lyrics"+firstTime;
        var lastWord_lyrics = "word_lyrics"+lastTime;

        var firstStart_time = "start_time"+firstTime;
        var lastStart_time = "start_time"+lastTime;
    }
    

    document.getElementById(firstWord_lyrics).style.borderTop = "2px solid black";
    document.getElementById(firstStart_time).style.borderTop = "2px solid black";
    document.getElementById(lastWord_lyrics).style.borderBottom = "2px solid black";
    document.getElementById(lastStart_time).style.borderBottom = "2px solid black";
                
}

function draw_word_content()
{
    if (firstTime === lastTime)
    {
        middleTime = firstTime;
    } else
    {
        middleTime = parseInt((lastTime + firstTime)/2);
    }
    
    var menu_lyricsWord = "menu_lyricsWord"+middleTime;
    var menu_lyricsWord_e = document.getElementById(menu_lyricsWord);
    menu_lyricsWord_e.style.display = "block";
}

function draw_frame(frame, word_lyrics, start_time)
{
    word_lyrics.style.backgroundColor = "#1ab7ea";
    start_time.style.backgroundColor = "#1ab7ea";
}

function displace_video(time)
{
    var v = document.getElementById("my_video");
    var respectlyValueFloat = parseFloat(time);
    v.currentTime = respectlyValueFloat;
}

function delete_lyricsWord()
{
    var respectlyValue = 0;
    
    // esta funcion me borra los frames que de la palabra
    delete_word_frames();
    
    // esta funcion me borra los bordes
    delete_word_border();
    
    // esta funcion me borra el input y las demás opciones
    delete_word_content();
}

function delete_word_frames()
{
    
}

function delete_word_border()
{
    
}

function delete_word_content()
{
    
}

function save_lyricsWord()
{
    
}

function edit_lyricsWord()
{
    
}

function replay_lyricsWord()
{
    
}