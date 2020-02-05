import React from "react";
import LoginForm from "./LoginForm";
import {Switch} from "react-router-dom";
import {Route} from "react-router";
import BlogPostList from "./BlogPostList";
import BlogPostListContainer from "./BlogPostListContainer";

class App extends React.Component {
    render() {
        return (
            <div>
                Hello !
                <Switch>
                    <Route path="/login" component={LoginForm} />
                    <Route path="/blog" component={BlogPostList} />
                    <Route path="/blog_list" component={BlogPostListContainer} />
                </Switch>
            </div>
        )
    }
}

export default App;