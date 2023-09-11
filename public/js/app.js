axios.defaults.baseURL = 'http://localhost:8000/api'
const access_token = localStorage.getItem('access_token')

const authLink = document.getElementById('auth');
if (access_token) {
    authLink.innerHTML = 'Выйти';
    authLink.addEventListener('click', logout);
} else {
    authLink.innerHTML = 'Войти';
    authLink.addEventListener('click', () => {
        window.location.href = "http://localhost:8000/login";
    });
}

async function logout() {
    await axios.post('/auth/logout', {access_token}, {
        headers: {
            Authorization: "Bearer " + access_token
        }
    })
        .then((response) => {
            localStorage.removeItem('access_token');

            window.location.href = "http://localhost:8000/";
        })
}
