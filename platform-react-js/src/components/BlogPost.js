import React, {Component} from 'react';
import moment from 'moment/moment';
import {Spinner} from "./Spinner";
import {Message} from "./Message";

export class BlogPost extends Component{
    render () {
        const {post} = this.props;

        if (null === post) {
            return (<Message message="Blog post does not exist" />)
        }

        return (
            <div className="card mb-3 mt-3 shadow-sm">
                <div className="card-body">
                    <h2>{post.title}</h2>
                    <p>{post.content}</p>

                    <p className="card-text border-top">
                        <small className={post.content}>
                            {moment(post.published).format("DD-MM-YY")} by&nbsp;
                            {post.author.name}
                        </small>
                    </p>
                </div>
            </div>
        );
    }
}