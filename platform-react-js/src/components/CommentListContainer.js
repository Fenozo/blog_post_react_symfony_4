

import React from 'react';
import {connect} from 'react-redux';
import {CommentList} from "./CommentList";
import {Spinner} from "./Spinner";
import {commentListFetch, commentListUnload} from "../actions/actions";

const mapeStateToProps = state => ({
    ...state.blogPost,
    ...state.commentList
})

const mapDispatchToProps = {
    commentListFetch,
    commentListUnload
}

class CommentListContainer extends React.Component {

    componentDidMount() {
        this.props.commentListFetch(this.props.blogPostId)
    }

    componentWillUnmount() {
        this.props.commentListUnload();
    }
    render() {
        const {isFetching, commentList} = this.props;

        if (isFetching) {
            return (<Spinner/>)

        }

        return (<CommentList commentList={commentList}/>)
    }
}

export default connect(mapeStateToProps, mapDispatchToProps)(CommentListContainer);
