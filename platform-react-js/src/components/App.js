import React from "react";
import {Switch} from "react-router-dom";
import {Route} from "react-router";
import BlogPostList from "./BlogPostList";
import Header from './Header';
import BlogPostListContainer from "./BlogPostListContainer";
import BlogPostContainer from "./BlogPostContainer";
import LoginForm from "./LoginForm";


class App extends React.Component {
    render() {
        return (
            <div>
                <Header />
                <Switch>
                    <Route path="/login" component={LoginForm} />
                    <Route path="/blog" component={BlogPostList} />
                    <Route path="/blog-posts/:id" component={BlogPostContainer} />
                    <Route path="/" component={BlogPostListContainer} />

                </Switch>
            </div>
        )
    }
}

export default App;