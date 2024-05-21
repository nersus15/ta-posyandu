var getRandomId = function (tipe = 'string', length = 5, between = null) {

    if (tipe == 'number' && between)
        return Math.random().toString(between).substr(2, length);
    else
        return Math.random().toString(20).substr(2, length)
};

var waktu = function (time = null, format = 'mysqltimestamp') {
    if (format == 'mysqltimestamp')
        format = 'YYYY-MM-DD HH:mm:ss';
    if (!time)
        time = new Date();

    return moment(time).format(format);
};

String.prototype.capitalize = function (tipe = 'first') {
    if (tipe != 'first') {
        var strings = this.split(' ');
        var text = [];

        strings.forEach(s => {
            text.push(s.charAt(0).toUpperCase() + s.slice(1));
        });
        return text.join(' ');
    }
    else
        return this.charAt(0).toUpperCase() + this.slice(1);

}
String.prototype.replaceAll = function (awal, baru) {
    var strings = this.split(awal);
    return strings.join(baru);
}
String.prototype.rupiahFormat = function () {
    var bilangan = this;
    var number_string = bilangan.toString(),
        sisa = number_string.length % 3,
        rupiah = number_string.substr(0, sisa),
        ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.') + ',00';
    }
    return rupiah;
}

String.prototype.isEmail = function (text) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(text);
}
function isFunction(variable) {
 return variable && {}.toString.call(variable) === '[object Function]';
}

function isKosong(variable){
    return (variable == undefined || variable == null || variable == '')
}

String.prototype.reverse= function(str){
    return __reverseStrig(str);
}

function __reverseStrig(str){
    return (str === '') ? '' :  __reverseStrig(str.substr(1)) + str.charAt(0);
}
// String.prototype.sandi = function(tipe = 'AN'){
//     $result = null;
//         $an = {
//             'a': 'n',
//             'b': 'o',
//             'c': 'p',
//             'd': 'q',
//             'e': 'r',
//             'f': 's',
//             'g': 't',
//             'h': 'u',
//             'i': 'v',
//             'j': 'w',
//             'k': 'x',
//             'l': 'y',
//             'm': 'z',
//             'A': 'N',
//             'B': 'O',
//             'C': 'P',
//             'D': 'Q',
//             'E': 'R',
//             'F': 'S',
//             'G': 'T',
//             'H': 'U',
//             'I': 'V',
//             'J': 'W',
//             'K': 'X',
//             'L': 'Y',
//             'M': 'Z',
//             '-': '+',
//             '_': '=',
//             '@': '#',
//             '&': '!',
//             ' ': '*',
//         };
//         $az = {
//             'a': 'z',
//             'b': 'y',
//             'c': 'x',
//             'd': 'w',
//             'e': 'v',
//             'f': 'u',
//             'g': 't',
//             'h': 's',
//             'i': 'r',
//             'j': 'q',
//             'k': 'p',
//             'l': 'o',
//             'm': 'n',
//             'n': 'm',
//             'o': 'l',
//             'p': 'k',
//             'q': 'j',
//             'r': 'i',
//             's': 'h',
//             't': 'g',
//             'u': 'f',
//             'v': 'e',
//             'w': 'd',
//             'x': 'c',
//             'y': 'b',
//             'z': 'a',

//             'A': 'N',
//             'B': 'O',
//             'C': 'P',
//             'D': 'Q',
//             'E': 'R',
//             'F': 'S',
//             'G': 'T',
//             'H': 'U',
//             'I': 'V',
//             'J': 'W',
//             'K': 'X',
//             'L': 'Y',
//             'M': 'Z',

//             '-': '+',
//             '_': '=',
//             '@': '#',
//             '&': '!',
//             ' ': '*',
//         };
//         $an_flip = array_flip($an);
//         $az_flip = array_flip($az);
//         if($type == "AN"){
//             foreach(str_split($text) as $char){
//                 if(isset($an[$char]))
//                     $result .= $an[$char];
//                 elseif(isset($an_flip[$char]))
//                     $result .= $an_flip[$char];
//             }
//         }else if($type == "AZ"){
//             foreach(str_split($text) as $char){
//                 if(isset($az[$char]))
//                     $result .= $az[$char];
//                 elseif(isset($az_flip[$char]))
//                     $result .= $az_flip[$char];
//             }
//         }
//         return $result;
// }