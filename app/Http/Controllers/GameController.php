<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Events\userGuess;
use App\Events\getImages;
use Auth;
use App\User;


class GameController extends Controller
{

    public function index(Request $request) { 

            if (Auth::User()->folder_name) { 

                if (Auth::user()->timeStart) { 
                    foreach(User::all() as $user) { 
                        $user->timeStart = 0; 
                        $user->save () ;
                    }
                }

                $test = scandir(public_path('images/'. Auth::User()->folder_name)) ;

                $cleanedFiles = array_diff($test, ['.', '..']);

                //return the response in json 
                return response()->json([
                    'images' => $cleanedFiles ,
                    'folder' => Auth::User()->folder_name,
                ]);

            }

        else {

            foreach(User::all() as $user) { 
                $user->timeStart = 1; 
                $user->save () ;
            }

             //get all folders in the main images folder ( images/"random folder" )
            $listOfFilesInMainImagesFolder = scandir(public_path('images')) ;

            // pick a random folder in a the main images folder 
            $random_key = array_rand(array_diff($listOfFilesInMainImagesFolder, ['.', '..']), 1);   

            // store the random folder 
            $randomFolder = $listOfFilesInMainImagesFolder[$random_key];  

            //display all files in that random folder 
            $files = scandir(public_path('images/' . $randomFolder));

            // remove unecessary dots 
            $cleanedFiles = array_diff($files, ['.', '..']);

            
            //broad cast all the images you get to others
            broadcast(new getImages($cleanedFiles , $randomFolder))->toOthers();

            // store the folder name to database 
            foreach(User::all() as $user) { 
                $user->folder_name = $randomFolder ;
                $user->save();
            }

             //return the response in json 
            return response()->json([
                'images' => $cleanedFiles ,
                'folder' => $randomFolder,
            ]);

        }
       

    }

    public function correctGuess(Request $request) {

        foreach(User::all() as $user ) { 

            if ($user->id  == $request->id ) { 
                $user->all_time_score += 50;
            }
            $user->all_time_score += 10;
            $user->folder_name = '' ;
            $user->timeStart = 1 ;
            $user->save();
        }

    }

    public function getScores() { 

        $scores = User::all();

        return response()->json([
            'data' =>  $scores ,
        ]);
    }


}
