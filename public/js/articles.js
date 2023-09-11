axios.defaults.baseURL = 'http://localhost:8000/api'

const articlesRef = document.getElementById('articles');
getArticles().then(res => articlesRef.innerHTML = createArticlesMarkup(res))

async function getArticles() {
    const {data} = await axios.get('/articles')
    return data.data
}

function createArticlesMarkup(articles) {
    return articles.map(({name,id}) => {
        return `<li id="${id}">${name}</li>`
    }).join('')
}
