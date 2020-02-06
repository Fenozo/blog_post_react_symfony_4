import React from 'react';
import {Link} from 'react-router-dom';
class BlogPostList extends React.Component {

    constructor(props) {
        super();
        console.log(props);

    }
    render() {
        const {posts, isFetching} = this.props;
        // isFetching === true ? console.log('not yet fetching') : console.log('it is fetching')
        // posts === undefined ? console.log('it is undefined') : console.log('---');

        if(isFetching) {
            return (<div><i className="fas fa-spinner fa-spin"/>Loading...</div>);
        }

        return (

            <div>
                <ul>
                    {posts && posts.map((post, _key) => (
                        <div className="card md-3 mt-3 shadow-sm" key={post.id}>
                            <div className="card-body">
                                <h3>
                                    <Link to={`/blog-posts/${post.id}`} >
                                        {post.title}
                                    </Link>
                                </h3>
                                <p className="card-text border-top">
                                    <small className="text-muted">
                                        {post.published}
                                    </small>
                                </p>
                            </div>
                        </div>
                    ))}
                </ul>
            </div>
        )
    }
}

export default BlogPostList;
