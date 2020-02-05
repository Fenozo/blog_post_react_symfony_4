import React from 'react';
import BlogPostList from "./BlogPostList";

class BlogPostListContainer extends React.Component {
    constructor(props) {
        super(props);

        this.posts = [{
            title : 'Hello'
        }];
    }

    render() {
        return (
            <div>
                <BlogPostList post={this.posts} />
                <div>Hello from BlogPostListContainer!</div>
            </div>
        )
    }
}

export default BlogPostListContainer;
