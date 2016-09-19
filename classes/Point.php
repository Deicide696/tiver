<?php 
namespace app\classes;

class Point {
    public $lat;
    public $long;
    public function __construct($lati, $longi) {
        $this->lat = $lati;
        $this->long = $longi;
    }
    //the Point in Polygon function
	

    public static function pointInPolygon($p, $polygon) {
    //if you operates with (hundred)thousands of points
    set_time_limit(60);
    $c = 0;
    $p1 = $polygon[0];
    $n = count($polygon);

    for ($i=1; $i<=$n; $i++) {
        $p2 = $polygon[$i % $n];
        if ($p->long > min($p1->long, $p2->long)
            && $p->long <= max($p1->long, $p2->long)
            && $p->lat <= max($p1->lat, $p2->lat)
            && $p1->long != $p2->long) {
                $xinters = ($p->long - $p1->long) * ($p2->lat - $p1->lat) / ($p2->long - $p1->long) + $p1->lat;
                if ($p1->lat == $p2->lat || $p->lat <= $xinters) {
                    $c++;
                }
        }
        $p1 = $p2;
    }
    // if the number of edges we passed through is even, then it's not in the poly.
    return $c%2!=0;
}
}

?>