function mobile_calendar(){
    var day=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    var month=['January','February','March','April','May','June','July','August','September','October','November','December'];
    var d=new Date();
    setText('mobile_calendar-day', day[d.getDay()]);
    setText('mobile_calendar-date', d.getDate());
    setText('mobile_calendar-month-year', month[d.getMonth()]+' '+(1900+d.getYear()));
};

function setText(id, val){
    if(val < 10){
        val = '0' + val;
    }
    document.getElementById(id).innerHTML = val;
};
window.onload = mobile_calendar;