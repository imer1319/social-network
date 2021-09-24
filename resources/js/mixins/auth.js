let user = document.querySelector('meta[name="user"]');

module.exports = {
    methods:{
        redirectIfGuest(){
            if (this.guest){
                return window.location.href = "/login"
            }
        }
    },
    computed:{
        currentUser(){
            return JSON.parse(user.content)
        },
        isAuthenticated(){
            return !! user.content
        },
        guest(){
            return ! this.isAuthenticated
        }
    }
}
