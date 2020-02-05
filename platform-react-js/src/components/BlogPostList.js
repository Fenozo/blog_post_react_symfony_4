import React from 'react';

class BlogPostList extends React.Component {

    constructor(props) {
        super();
        console.log(props);

    }
    render() {
        const {posts} = this.props;
        return (

            <div>
                <ul>
                    {posts && posts.map(post => (<li>{post.title}</li>))}
                </ul>
            </div>
        )
    }
}

export default BlogPostList;
