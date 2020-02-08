import React from 'react';
import {Message} from "./Message";
import moment from 'moment/moment';
export class CommentList extends React.Component{
    render () {

        const {commentList} = this.props;

        console.log(commentList)

        if (null === commentList) {
            return (<Message message="No comments yet" />)
        }

        return (
                <div className="card mb-3 mt-3 shadow-sm">
                    {
                        commentList && commentList.map((comment, comment_key) => {
                            return (
                                <div className="card-body border-bottom" key={comment_key}>
                                    <p className="card-text">
                                        {comment.content}
                                    </p>
                                    <p className="card-text">
                                        <small className="text-muted">
                                            {moment(comment.published).fromNow()} by&nbsp;
                                            {comment.author.name}
                                        </small>
                                    </p>
                                </div>
                            )
                        })
                    }
                </div>
            )
    }
}