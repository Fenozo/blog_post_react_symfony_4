import {requests} from "../agent";
import {
    BLOG_POST_ERROR,
    BLOG_POST_LIST_ADD,
    BLOG_POST_LIST_ERROR,
    BLOG_POST_LIST_RECEIVED,
    BLOG_POST_LIST_REQUEST,
    BLOG_POST_RECEIVED,
    BLOG_POST_REQUEST,
    BLOG_POST_UNLOAD,
    COMMENT_LIST_ERROR,
    COMMENT_LIST_RECEIVED,
    COMMENT_LIST_REQUEST,
    COMMENT_LIST_UNLOAD,
    USER_LOGIN_SUCCESS
} from "./contains";




export const blogPostListRequest = () =>({
    type : BLOG_POST_LIST_REQUEST,
});

export const blogPostListReceived = (data) =>({
    type : BLOG_POST_LIST_RECEIVED,
    data
});

export const blogPostListError = (error) =>({
    type : BLOG_POST_LIST_ERROR,
    error
});


export const blogPostListFetch = () => {
    return (dispatch) => {
        dispatch(blogPostListRequest());
        return requests.get('api/blog_posts')
            .then(response => dispatch(blogPostListReceived(response)))
            .catch(error => dispatch(blogPostListError(error)))
    }
};

export const blogPostRequest = () => ({
    type : BLOG_POST_REQUEST,
});

export const blogPostError = (error) =>({
    type : BLOG_POST_ERROR,
    error
});

export const blogPostReceived = (data) =>({
    type : BLOG_POST_RECEIVED,
    data
});

export const blogPostUnload = () => ({
    type: BLOG_POST_UNLOAD,
});

export const blogPostFetch = (id) => {
    return (dispatch) => {
        dispatch(blogPostRequest());
        return requests.get(`api/blog_posts/${id}`)
            .then(response => dispatch(blogPostReceived(response)))
            .catch(error => dispatch(blogPostError(error)))
    }
};


export const commentListRequest = () => ({
    type : COMMENT_LIST_REQUEST,
});

export const commentListError = (error) =>({
    type : COMMENT_LIST_ERROR,
    error
});

export const commentListReceived = (data) =>({
    type : COMMENT_LIST_RECEIVED,
    data
});

export const commentListUnload = () => ({
    type: COMMENT_LIST_UNLOAD,
})

export const commentListFetch = (id) => {
    return (dispatch) => {
        dispatch(commentListRequest());
        return requests.get(`my_api/blog_posts/${id}/comments`)
            .then(response => dispatch(commentListReceived(response)))
            .catch(error => dispatch(commentListError(error)))
    }
};

export const userLoginSuccess = (token, userId) => {
    return {
        type : USER_LOGIN_SUCCESS
    }
}

export const userLoginAttempt  = (username, password) =>{
    return (dispatch) => {
        return requests.post('api/login_check', {username, password})
            .then(response => console.log(response))
            .catch(error => {
                console.log('Login failed')
            })

    }
};

export const blogPostAdd = () => ({
    type: BLOG_POST_LIST_ADD,
    data : {
        id : Math.floor(Math.random() *100 +3),
        title : 'A newly added blog post'
    }
});

