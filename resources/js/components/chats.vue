<template>
            <div class="col-md-7 mt-4">
                <div class="card " style = 'height:500px;width:780px;background-color:#243447' >
                    <div class="card-header text-white font-weight-bold">Chats ⌨ 
                        <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-target="#usersOnline">
                            Users online <span class = 'badge badge-primary'>{{this.users.length}}</span>
                        </button>
                        </div>

                    <div class="card-body custom_scroll" style ="height:500px ; overflow-y:scroll " v-chat-scroll >

                        <div class="row">
                            <div class="col-md-12">

                                <div v-for="message in messages" :key="message.id"  >
                                    
                                    <div class="card p-2 wrong mb-4  text-white font-weight-bold border border-primary " style = 'background-color:#243447 ; width : 500px ;     border-width:3px !important;'  v-if ="user.name == message.user.name"> 
                                            <div class="card-header">
                                                <h6 class = 'font-weight-bold'>@{{message.user.name}}</h6>
                                            </div>
                                            <div class="card-content">
                                                <p class = 'ml-3 '>{{message.message}}</p>
                                            <h6 class=  'float-right font-weight-bold '>{{message.time_ago}} ⏲</h6>
                                            </div>
                                            
                                    </div>
                                      <div class="card chats  p-2 mb-4 text-white font-weight-bold border border-info float-right " style = 'background-color:#243447 ; width : 500px;     border-width:3px !important;
'  v-if ="user.name != message.user.name"> 
                                            <div class="card-header ">
                                                <h6 class = 'font-weight-bold'>@ {{message.user.name}}</h6>
                                            </div>
                                            <div class="card-content">
                                                <p class = 'ml-3 '>{{message.message}}</p>
                                            <h6 class=  'float-right font-weight-bold  '>{{message.time_ago}} ⏲</h6>
                                            </div>
                                            
                                        </div>

                                </div>

                                    
                                        
                            </div>

                        </div>
                      
                       
                       
                  
                    </div>
                
            </div>
            
            
             <div class = 'mt-3'>
                    <div class="row">
                        <div class="col-md-12" >
                            <input type="text text-white"  style = " background-color:#243447; color: white ; font-weight:bold " class="form-control" v-model = 'message' placeholder="Enter message 📩" @keydown="isTyping" @keyup.enter="sendMessage">
                        </div>
                        <span class = 'ml-4' v-if="someoneTyping">{{someoneTyping}} is typing .... ⌨</span>
                    </div>
            </div>



<!-- Modal -->
<div class="modal fade" id="usersOnline" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style = 'background-color:#141d26'>
  <div class="modal-dialog" role="document">
    <div class="modal-content" style ='background-color:#243447'>
      <div class="modal-header">
        <h5 class="modal-title text-white font-weight-bold" id="exampleModalLabel">User currently online</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

             <div class="card text-white font-weight-bold" style = 'height:250px;background-color:#243447'>
             
                <div class="card-body">
                     <ul class="list-group" style ='background-color:#243447'>
                         <li class="list-group-item border border-info" style = 'background-color:#243447' >
                            User name
                            <span class = 'float-right'>All time scores</span>
                        </li>
                    </ul>
                  <br>
                    
                    
                    <ul class="list-group text-white font-weight-bold" style = 'background-color:#243447'>
                        <li class="list-group-item" v-for="user in users" :key ='user.id'  style = 'background-color:#243447'>
                            {{user.name}}
                            <span class = 'badge badge-primary float-right'> {{user.all_time_score}} pts </span>
                            </li>
                    </ul>
                </div>

            </div>

        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>















               
        </div>
</template>

<script>
    export default {
        mounted() {

            Echo.join('message-sent')
                .here(user => { 
                    this.users = user ;
                })
                .joining(user => { 
                    this.users.push(user) ;

                })
                .leaving(user => { 
                    this.users = this.users.filter(u => u.id != user.id) ;
                })
                .listen('messageSent' , (res) => {
                    this.messages.push({
                        time_ago: 'just now',
                        message : res.message.message , 
                        user : res.user
                    });
                })
                .listenForWhisper('typing', (res) => {

                    this.someoneTyping = res.name ;

                    if (this.typingTimer) { 
                        clearTimeout(this.typingTimer);
                    }
                    
                    this.typingTimer = setTimeout(() => {
                        this.someoneTyping = false ;
                    }, 1500);

                })
    
            this.getAllChats();
            
        },
        data() { 
            return { 
                messages : [],
                message : '',
                users : [],
                someoneTyping : false ,
                typingTimer : false ,
            }
        } , 
        props : ['user'],
        methods : { 
            getAllChats() { 
                axios.get('/chats').then(res => { 
                    this.messages = res.data.chatMessages;
                    console.log(this.messages);
                });
            },
            sendMessage() {

                this.messages.push({
                    user : this.user ,
                    message : this.message ,
                    time_ago : 'just now'
                });

                 axios.post('/send/message',{message: this.message})
                .then((response)=>{
                    console.log(response)

                });

                this.message = " "; 

            },
            
          isTyping() {
                Echo.join('message-sent')
                    .whisper('typing' , this.user);
            },
       
            
        }
    }
</script>

