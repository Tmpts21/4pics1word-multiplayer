<template>

    <div class="col-md-5 mt-4 ">

        <div class="row justify-content-center">
            <div v-if="this.user.email == 'ian@gmail.com' && !folder" >
                <button  @click="getImages" class = 'btn btn-primary ml-4 text-center'>Start</button>
            </div>
        </div>
      
    <div v-show="folder" class="ml-5 wrong alert alert-success font-weight-bold " role="alert" style = 'width:350px;'>
        Hint : This word has {{folder.length}} characters
    </div>
    <br>
        <div v-if ="game && folder"  >
            <div class="row justify-content-center"  > 
                <div class="col-lg-5 text-center p-1 game " v-for="(image , index) in images" :key ="index" >
                    <img :src="'/images/'+folder+'/' +image" alt="image" class="img-thumbnail img " />
                </div>
            </div>    

            <div class="mb-2 row justify-content-center">
                        <div class="p-2" style = '' v-if ="displayGuesser">
                            <input  @keyup.enter="enterGuess" type="text" class="form-control ml-5" v-model ='guess'  placeholder="Enter your wildest guess 🔍" style="text-align:center ;background-color:#243447; color: white ; font-weight:bold ; width:250px; ">
                        </div>
            </div>
        </div>
    <div v-show="isWrong" class="ml-2 alert alert-danger wrong font-weight-bold border border-danger" role="alert" >
        Wrong guess 💩👎🤪😅💩
    </div>


<div class="modal fade"  id="test" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style = 'background-color:#141d26;'>
    <br>
    <div class="text-center">
                    <img src="/images/toby.gif" class="rounded" alt="Toby dancing" height="150px">
    </div>
    <br>
    <div class="row p-4 justify-content-center" style ='background-color:#243447'>
      <div class="col-lg-12">
            <div class = 'font-weight-bold text-white text-center' style = 'font-size:35px'>
                <p>{{correctGuess}}</p> 
                <p>game will restart in {{this.timeLimit}}</p>  

        <div class="row justify-content-center">

            <div class="card text-white  font-weight-bold" style = 'height:250px;background-color:#243447'>
                <div class="card-header">
                    <h5 class = 'font-weight-bold'>Leaderboards</h5>
                </div>
                <div class="card-body " style = 'font-size:15px;'>
                    
                    <ul class="  list-group text-white font-weight-bold" style = 'background-color:#243447'>
                        <li class="list-group-item " v-for="(score , index ) in scores" :key ='index'  style = 'background-color:#243447'>
                            🏆 top {{index + 1}} {{score.name}}
                            <span class = 'ml-2 badge badge-primary float-right'>  {{score.all_time_score}} pts </span>
                            </li>
                    </ul>
                </div>

            </div>
    </div>

                    
                    

                </div>
            </div>
      </div>
  </div>
</div>




</template>

<script>
    export default {
        mounted() {
            this.getScores();
                if(this.user.folder_name) { 
                    this.getImages();
                }

               Echo.join('user-guess')
                .here(user => { 
                    console.log(this.user);
                })
                .listen('getImages' , (res) => {
                    console.log(this.timeStart);
                    if ( this.user.timeStart ) { 
                        this.startCountdown = true ; 
                    };
                    this.timer();
                    if (!this.timeStart) { 
                        this.images = res.images ; 
                        this.folder = res.folder ; 
                        this.displayGuesser = true ;   
                        this.start = true ;  
                    }
                })
                .listenForWhisper('correctGuess', (res) => {
                    this.correctGuess = res.name + ' guessed the word correctly  👏👏👏 ' ;
                    this.congratsTrack.play();
                    $("#test").modal('show');
                    this.timeLimit = 10 ;
                    this.timer();
                    
                })
               
        },
        data() { 
            return { 
                congratsTrack : new Audio('audio/final.mp3'),
                counter : 0 ,
                images  : [],
                folder : '' ,
                guess : '',
                displayGuesser : false ,
                game : true  , 
                congratulation : false ,
                startCountdown : true ,
                timeLimit : 10 ,
                timeStart: true ,
                correctGuess : null ,
                scores : null , 
                isWrong : false , 
                isWrongboolean : false ,
              
            }
        } , 
        props: ['user'],
        methods : { 

            getImages() {         
                    this.startCountdown = true ;

                     axios.get('/getImages')
                        .then( (res) => { 
                        console.log(res.data.images);
                        this.images = res.data.images ; 
                        this.folder = res.data.folder ; 
                        this.displayGuesser = true ;
                        });
            } , 
            enterGuess() {

                if (this.guess == this.folder) { 
                    this.game = false ; 
                    this.guessedCorrectly();
                    this.correctGuess = this.user.name +  ' guessed the word correctly  👏👏👏 ' ; 
                    this.congratsTrack.play();
                    $("#test").modal('show');
                    axios.post('/correctGuess' , {
                        id : this.user.id
                    })
                        .then((res) => { 
                            console.log(res);
                        });
                    this.timer();
                }
                else { 
                    this.isWrong = true

                     if (this.isWrongboolean) { 
                        clearTimeout(this.isWrongboolean);
                    }

                    this.isWrongboolean = setTimeout(() => {
                        this.isWrong = false
                    }, 1500);



                }
                                
                this.guess = ''; 
                
                    
            },
           timer () { 
               if (this.timeLimit > 0 )  { 
                   console.log(this.timeLimit);
                   setTimeout(()=> { 
                       this.timeLimit -= 1
                       this.timer();
                   } , 1000);
               }
               else { 
                   this.startCountdown  = false ;
                   this.timeStart  = false ;
                   this.getImages(); 
                    if(this.folder) { 
                        location.reload();
                    }
               }
           },
             guessedCorrectly() {
                Echo.join('user-guess')
                    .whisper('correctGuess' , this.user);
            },
            getScores() { 
                axios.get('/gameScores')
                .then((res) => {
                    this.scores = res.data.data ;
                })
            },
       
        
 
        }
    }
</script>
