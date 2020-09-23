<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Generator;
use PDF;
use Imagick;
use Storage;

class ExportController extends Controller
{
    public function __construct () {
        $this->middleware('auth:api', ['except' => ['login', 'unauthorized']]);

    }

    public function unescape($str) {
        $ret = '';
        $len = strlen ( $str );
        for($i = 0; $i < $len; $i ++) {
            if ($str [$i] == '%' && $str [$i + 1] == 'u') {
                $val = hexdec ( substr ( $str, $i + 2, 4 ) );
                if ($val < 0x7f)
                    $ret .= chr ( $val );
                else if ($val < 0x800)
                    $ret .= chr ( 0xc0 | ($val >> 6) ) . chr ( 0x80 | ($val & 0x3f) );
                else
                    $ret .= chr ( 0xe0 | ($val >> 12) ) . chr ( 0x80 | (($val >> 6) & 0x3f) ) . chr ( 0x80 | ($val & 0x3f) );
                $i += 5;
            } else if ($str [$i] == '%') {
                $ret .= urldecode ( substr ( $str, $i, 3 ) );
                $i += 2;
            } else
                $ret .= $str [$i];
        }
        return $ret;

    }

    public function tag(Request $request, $canvas, $filetype) {      
        $hash = $request->input('hash');
        $size = $request->input('size');
        $color = $request->input('color');

        $color = $color ? true : false;
        

        if(!empty($hash)) {
            $ext = '';
            switch($filetype) {
                case 'svg':
                    $ext = 'svg';
                break;
                case 'png':
                    $ext = 'png';
                break;
                case 'jpg':
                    $ext = 'jpg';
                break;
                case 'jpeg':
                    $ext = 'jpg';
                break;
                case 'pdf':
                    $ext = 'pdf';
                break;
                default:
                    $ext = 'svg';
                break;
            }
            $size = $size ? $size : 150;

            $url = 'http://tagbike.com.br/tag/'.$hash;

            $qrcode = new Generator;
            $qrcode->format('svg');
            $qrcode->size($size);

            if($color) {
                $qrcode->gradient(65,175, 39,105,50,137,'vertical');
            }

            $code = $qrcode->generate($url);
            $tag = Storage::get('/public/tag.svg');

            $code_svg = new \SimpleXMLElement($code);
            $code_svg->addAttribute("x", '29');
            $code_svg->addAttribute("y", '200');
            

            $tag_svg = new \SimpleXMLElement($tag);
            $tag_text = $tag_svg->children();
            $tag_svg->g->text->tspan = 'ID: '.$hash;

            $code_dom = dom_import_simplexml($code_svg);
            $tag_dom  = dom_import_simplexml($tag_svg);

            
            
            $code_dom = $tag_dom->ownerDocument->importNode($code_dom, true);

            $tag_dom->appendChild($code_dom);

            $svg = '';
            if($canvas === 'qrcode') {
                $svg = $code_svg->asXML();
            } else {
                $svg = $tag_svg->asXML();
            }
            
            if($ext === 'svg') {
                echo $svg;
            } 
        } else {
            return response()->json('error', 404);
        }        
    }
}
